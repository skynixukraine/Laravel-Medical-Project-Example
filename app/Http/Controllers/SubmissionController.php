<?php

namespace App\Http\Controllers;

use App\Events\QuestionAnsweredEvent;
use App\Events\QuestionAskedEvent;
use App\Events\SubmissionAnsweredEvent;
use App\Events\SubmissionEvaluatedEvent;
use App\Http\Requests\SubmissionPostRequest;
use App\Models\Partner;
use App\Models\Question;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{

    public function indexOpen(Request $request) {

        $user = Auth::user();

        $submissions = Submission::with('partner:id,name,language')
            ->where('status', 'open')
            ->orWhere (function ($query) use ($user) {
                $query->whereIn('status', ['assigned', 'permanently_assigned'])
                      ->where('assigned_to_user_id', $user->id);
            })
            ->select('id', 'age', 'gender', 'responsetime', 'created_at', 'due_at', 'status', 'assigned_at', 'partner_id')
            ->orderBy('due_at')
            ->get();

        $submissionsWithMinutesLeft = $submissions->map(function ($submission) {
            $submission->minutesLeft = $submission->minutesLeft();
            return $submission;
        });

        return $submissionsWithMinutesLeft;
    }

    public function indexAnsweredByMe(Request $request) {
        $user = Auth::user();
        $submissions = $user->answeredSubmissions()
            ->select('id', 'age', 'gender', 'responsetime', 'created_at', 'answered_at', 'stars')
            ->orderByDesc('answered_at')
            ->get();
        return $submissions;
    }

    public function statsByUser(Request $request) {
        $user = Auth::user();
        $answeredSubmissionsCount = $user->answeredSubmissions()
            ->count();
        $answeredSubmissionsLastMonthCount = $user->answeredSubmissions()
            ->where('answered_at', '>=', new Carbon('first day of last month'))
            ->where('answered_at', '<', Carbon::today()->day(1))
            ->count();
        $averageRating = $user->answeredSubmissions()
            ->select([
                      DB::raw('COUNT(*) as number_stars'),
                      DB::raw('AVG(stars) as average_stars')])
            ->whereNotNull('stars')
            ->groupBy('assigned_to_user_id')
            ->first();

//        \Log::info("insgesamt beantwortet ");
//        \Log::info($answeredSubmissionsCount);
//        \Log::info("beantwortet letzten Monat");
//        \Log::info($answeredSubmissionsLastMonthCount);
//        \Log::info("Bewertung");
//        \Log::info($averageRating);

        return ["answered_submissions"            => $answeredSubmissionsCount,
                "answered_submissions_last_month" => $answeredSubmissionsLastMonthCount,
                "average_rating"                  => (isset ($averageRating["average_stars"])) ? round($averageRating["average_stars"],1) : null,
                "average_rating_number_stars"     => (isset ($averageRating["number_stars"]))  ? $averageRating["number_stars"] : 0
        ];
    }

    /**
     * e.g. api/submissions/7xedttruj8
     *
     * @param Request $request
     * @param Submission $submission
     * @return Submission
     */
    public function show(Request $request , Submission $submission) {

        if ($submission->tooOld()) {
            return response(['errors' => ['Submission' => ['not found']]], '404');
        }

        // only show submission, if it was created at this partner website
        $partnerID = $request["partner_id"] ?: "ohn";
        $partner = Partner::where('partner_id', $partnerID)->first();
        if (!$partner || $submission->partner_id != $partner->id) {
            return response(['errors' => ['submission' => ['not found']]], '404');
        }

        $submission->symptoms;
        $submission->minutesLeft = $submission->minutesLeft();
        $submission->questions = $submission->questions()->with('askedBy:id,title,first_name,last_name')->get();

        // if the submission has status 'open' or 'assigned', then answered_by is empty
        if ($submission->status == 'answered') {
            // todo: can this be refactored ?
            $user = $submission->assignedTo()->select(['user_id', 'photo', 'gender', 'title', 'first_name', 'last_name', 'street', 'city', 'country'])->first();
            $user->photo_url = $user->getPhotoUrl();
            $submission->answered_by = $user;
        }
        else {
            $submission->answered_by = null;
        }

        return array_except($submission->toArray(),
            ['diagnosis_possible', 'diagnosis', 'requires_doctors_visit', 'did_recommend_medicine', 'recommended_medicine']);
    }

    /**
     * e.g. api/submission/32
     * This endpoint is meant to be used by logged in users (doctors)
     * submissions are requested by id and not by submission_id
     *
     * @param Request $request
     * @return Submission
     */
    public function showById($id) {
        $user = Auth::user();
        $submission = Submission
            ::with('symptoms')
            ->with('partner:id,name,language')
            ->with(array('questions' => function($query) {
                $query->orderBy('created_at', 'DESC');
            }))
            ->whereRaw('id = ? AND 
                    (status = "open" OR assigned_to_user_id = ?) ',
                [$id, $user->id])
            ->select('id', 'side', 'affected_area', 'since', 'since_other', 'treated', 'treatment', 'other_symptoms', 'description',
                'responsetime', 'gender', 'age', 'due_at' ,
                'status', 'closeup_image_id', 'closeup2_image_id', 'overview_image_id', 'assigned_to_user_id', 'assigned_at',
                'answer', 'answered_at',
                'stars', 'feedback',
                'created_at', 'updated_at', 'partner_id')
            ->first();

        if (!$submission) {
            return response(['errors' => ['submission_id' => ['not found']]], '404');
        }

        $submission->minutesLeft = $submission->minutesLeft();
        return $submission;
    }

    public function assign($id)
    {
        $user = Auth::user();

        $submission = Submission::where('id', $id)->first();
        if (!$submission) {
            return response(['errors' => ['id' => ['Fall nicht gefunden']]], '404');
        }

        if ($submission->status != 'open') {
            return response(['errors' => ['id' => ['Dieser Fall ist nicht (mehr) verfügbar.']]], '400');
        }

        // It is not allowed to have another submission assigned at the same time
        $assignedSubmissions = $user->assignedSubmissions()->where('status', 'assigned')->count();
        if ($assignedSubmissions) {
            return response(['errors' => ['id' => ['Sie bearbeiten bereits einen anderen Fall.']]], '400');
        }

        // We don´t want to downgrade from permanently_assigned to assigned
        // Usually it will be a change from open to assigned
        if ($submission->status != 'permanently_assigned') {
            $submission->status = 'assigned';
            $submission->assigned_to_user_id = $user->id;
            $submission->assigned_at = Carbon::now();
            $submission->save();
        }

        return response(["success" => true]);
    }

    public function release($id)
    {
        $user = Auth::user();

        $submission = Submission::where('id', $id)->first();
        if (!$submission) {
            return response(['errors' => ['id' => ['Fall nicht gefunden']]], '404');
        }

        if (($submission->status == 'setup' ) ||
            ($submission->status == 'answered' )) {
            return response(['errors' => ['id' => ['Fehler']]], '400');
        }

        if ($submission->assigned_to_user_id != $user->id) {
            return response(['errors' => ['id' => ['Dieser Fall wird/wurde von einem Kollegen bearbeitet.']]], '400');
        }

        if ($submission->status == 'permanently_assigned') {
            return response(['errors' => ['id' => ['Da Sie zu dem Fall bereits Rückfragen gestellt haben, 
            können Sie den Fall nicht mehr abgeben.']]], '400');
        }

        $submission->status = 'open';
        $submission->assigned_to_user_id = null;
        $submission->assigned_at = null;
        $submission->save();

        return response(["success" => true]);
    }

    public function answer (Request $request, $id) {
        $user = Auth::user();

        $submission = Submission::where('id', $id)->first();
        if (!$submission) {
            return response(['errors' => ['id' => ['Fall nicht gefunden']]], '404');
        }

        if ($submission->assigned_to_user_id != $user->id) {
            return response(['errors' => ['id' => ['Dieser Fall ist nicht (mehr) verfügbar.']]], '400');
        }

        if (!($submission->status == 'assigned' ||
              $submission->status == 'permanently_assigned')) {
            return response(['errors' => ['id' => ['Dieser Fall ist Ihnen nicht (mehr) zugewiesen.']]], '400');
        }

        $answer = $request->answer;
        if (strlen(trim($answer)) < 1) {
            return response(['errors' => ['id' => ['Bitte beantworten Sie den Fall so ausführlich wie möglich.']]], '400');
        }

        // we could do some additional validations for
        // diagnosis_possible, diagnosis, did_recommend_medicine, recommended_medicine, requires_doctors_visit
        // but everything is already validated in the frontend

        $submission->status = 'answered';
        $submission->answer = strip_tags($answer);
        $submission->answered_at = Carbon::now();
        $submission->diagnosis_possible = (bool)$request->diagnosis_possible;
        if ($submission->diagnosis_possible) {
            $submission->diagnosis = strip_tags($request->diagnosis);
        }
        $submission->did_recommend_medicine = (bool)$request->did_recommend_medicine;
        if ($submission->did_recommend_medicine) {
            $submission->recommended_medicine = strip_tags($request->recommended_medicine);
        }
        $submission->requires_doctors_visit = (bool)$request->requires_doctors_visit;
        $submission->save();

        event(new SubmissionAnsweredEvent($submission));

        return response(["success" => true]);
    }

    public function question (Request $request, $id) {
        $user = Auth::user();

        $submission = Submission::where('id', $id)->first();
        if (!$submission) {
            return response(['errors' => ['id' => ['Fall nicht gefunden']]], '404');
        }

        if (!$submission->questionAllowedForUser($user)) {
            return response(['errors' => ['id' => ['Sie haben keine Berechtigung, Rückfragen zu diesem Fall zu stellen.']]], '400');
        }

        $question = filter_var(trim($request->question), FILTER_SANITIZE_STRING);
        if (strlen($question) < 1) {
            return response(['errors' => ['id' => ['Bitte formulieren Sie Ihre Frage.']]], '400');
        }

        $question = Question::create([
            'question' => strip_tags($question),
            'submission_id' => $submission->id,
            'asked_by_user_id' => $user->id
        ]);

        // After a question was asked, the status must be permanently_assigned
        $submission->status = 'permanently_assigned';
        $submission->assigned_to_user_id = $user->id;
        $submission->save();

        event(new QuestionAskedEvent($question));

        return response(["success" => true]);
    }

    // For clients requesting with submission_id
    public function showPhoto(Submission $submission, $image_id, $width = null) {

        if ($submission->tooOld()) {
            return response("not found", '404');
        }

        // check if $image_id is either the closeup_image_id or the overview_image_id of this submission
        if ($submission->closeup_image_id <> $image_id &&
            $submission->closeup2_image_id <> $image_id &&
            $submission->overview_image_id <> $image_id) {
            return response(['errors' => ['image_id' => ['not found']]], '404');
        }

        // if the original version is not available, we will return 404
        // this will check either s3 or /storage/app, depending on the environment
        $url_original = "submissions/" . $image_id . '.jpg';
        if (!Storage::exists($url_original)) {
            return response(['errors' => ['Photo' => ['not found']]], '404');
        }

        // Lets copy the original file to cached, if not done already
        // either from S3/submissions or from /storage/app/submissions
        $url_cached = "cached/".$url_original;
        if (!Storage::disk('local')->exists($url_cached)) {
            $imageOriginal = Storage::get($url_original);
            Storage::disk('local')->put($url_cached, $imageOriginal);
        }

        // A) a resized version is requested
        if ($width) {
            $url_cache_resized = "cached/submissions/" . $image_id . "-" . $width . ".jpg";
            // if the resized version doesnt exist yet, we need to create it
            if (!Storage::disk('local')->exists($url_cache_resized)) {
                $imageResized = Image::make(storage_path('app/'.$url_cached))
                    ->widen($width, function ($constraint) {
                        $constraint->upsize();
                    })
                    ->save(storage_path('app/'.$url_cache_resized));
            }
            $url = $url_cache_resized;
        }
        // B) the original version is requested, we use the cached version
        else {
            $url = $url_cached;
        }

        $photo = Storage::disk('local')->get($url);
        $response = Response::make($photo, 200);
        $response->header("Content-Type", "image/jpeg");
        return $response;
    }

    // For users (doctors) who should not know the submission_id, therefore use the id of a submission
    public function showPhotoBySubmissionId($id, $image_id, $width = null) {
        $submission = Submission::where('id', $id)->first();
        if (!$submission) {
            return response(['errors' => ['id' => ['not found']]], '404');
        }
        return $this->showPhoto($submission, $image_id, $width);
    }

    /**
     * Returns error response, if upload was not correct
     * According to fineuploader, older IEs require text/plain (https://docs.fineuploader.com/branch/master/endpoint_handlers/traditional.html)
     */
    private function returnUploadError($message) {
        return response([
            "success"       => false,
            "error"         => $message,
            "preventRetry"  => true]
            , 400)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * important: Uploads require a central storage.
     * For a single testing server, the local filesystem is fine.
     * But for more than 1 server, something like S3 is required.
     */
    public function uploadPhoto(Request $request) {

        // validation 1: file is part of the request
        if (!$request->hasFile('qqfile')) {
            return $this->returnUploadError('no file uploaded');
        }

        // validation 2: file uploaded successfully
        if (!$request->file('qqfile')->isValid()) {
            return $this->returnUploadError('Foto upload fehlgeschlagen.');
        }

        $file = $request->qqfile;

        // validation 3: is an image
        $extension = $file->extension();
        if (!in_array($extension, ['jpeg', 'jpg', 'png', 'bmp'])) {
            return $this->returnUploadError('Bitte nur .jpg, .png oder .tiff Fotos verwenden.');
        }

        // generate an intervention image object
        $image = Image::make($file->path());

        // validation 4: minimum 300px width
        $minWidth = 300;
        if ($image->width() < $minWidth) {
            return $this->returnUploadError('Bitte nur Fotos mit einer Mindestauflösung von '.$minWidth.' Pixel Breite verwenden.');
        }

        // Resize to 2000px width if the uploaded image is bigger,
        // convert in any case to jpg
        $img = $image->widen(2000, function ($constraint) {
            $constraint->upsize();
        })->encode('jpg', 95);

        // save the jpg in storage/app/uploads with random filename
        $randomFilename = str_random(15);
        Storage::put('uploads/' . $randomFilename . '.jpg', $img->__toString());

        // return filename in /uploads folder (without .jpg at the end)
        return response([
            "success" => true,
            "image_id" => $randomFilename
            ])
            ->header('Content-Type', 'text/plain');
    }

    /**
     * This endpoint does not really delete previously uploaded pictures.
     *
     * The problem is, that fineuploader.js can be configured, that the user can only upload 1 file.
     * But if the user did already successfully upload an image, he can not just upload a different image instead.
     * Only if the user clicks on delete to delete the already existing image, he can choose a new image for upload.
     * So, we just return "ok, image was deleted" that fineuploader is happy and the user can upload a different image.
     * todo: maybe find a better solution for file uploads in the future. Or implement this function
     */
    public function fakeUploadPhotoDelete(Request $request) {
        return ["success" => true];
    }

    public function store(SubmissionPostRequest $request) { //

        // for backward compatibility assume partner_id="ohn" if param is not present
        $partnerID = ($request->has("partner_id")) ? $request["partner_id"] : "ohn";
        $partner = Partner::findByPartnerId($partnerID);

        // save submission (some fields will be set in the payment step)
        $data = array_merge($request->all(), [
            'status'        => 'setup',
            'city'          => '', // we need to set something, because this field has no default in the database
            'country'       => 'DE',
            'responsetime'  => null,
            'due_at'        => null,
            'partner_id'    => $partner->id,
            'submission_id' => Submission::generateSubmissionID(),
            'transaction_id'=> Submission::generateTransactionID(),
        ]);
        $submission = Submission::create($data);
        $submission->symptoms()->attach($request->symptoms);

        // move uploaded pictures from /uploads to /submissions
        Storage::move('uploads/' . $request->closeup_image_id. '.jpg',
                      'submissions/' . $request->closeup_image_id . '.jpg');
        Storage::move('uploads/' . $request->closeup2_image_id. '.jpg',
                      'submissions/' . $request->closeup2_image_id . '.jpg');
        Storage::move('uploads/' . $request->overview_image_id. '.jpg',
                      'submissions/' . $request->overview_image_id . '.jpg');

        // return submission_id for the next form (payment)
        return ["submission_id"  => $submission->submission_id,
                "transaction_id" => $submission->transaction_id];
    }

    public function getPricingTable(Request $request) {
        $partner_id = ($request->has('partner_id')) ? $request["partner_id"] : "ohn";
        if ($partner_id == "ita") $pricingTable = Submission::$pricingByResponsetimeITA;
        elseif ($partner_id == "sna") $pricingTable = Submission::$pricingByResponsetimeSNA;
        else $pricingTable = Submission::$pricingByResponsetimeOHN;

        return $pricingTable;
    }

    public function evaluate(Submission $submission, Request $request) {

        // the submission must already be answered
        if ($submission->status != 'answered') {
            return response(['errors' => ['id' => ['invalid']]], '400');
        }

        // the submission must not yet have a feedback
        if ($submission->feedback) {
            return response(['errors' => ['id' => ['invalid']]], '400');
        }

        $messages = [
            'stars.numeric'     => 'Vergeben Sie bitte zwischen 1 und 5 Sterne.',
            'stars.between'     => 'Vergeben Sie bitte zwischen 1 und 5 Sterne.',
            'feedback.required' => 'Bitte schreiben Sie eine kurze Bewertung.'
        ];

        Validator::make($request->all(), [
            'stars'    => 'numeric|between:1,5',
            'feedback' => 'required'
        ], $messages)->validate();

        $submission->feedback = $request->feedback;
        $submission->stars = $request->stars;
        $submission->save();

        event(new SubmissionEvaluatedEvent($submission));

        return $submission->only('feedback', 'stars');
    }

    public function answerQuestion(Submission $submission, Question $question, Request $request) {

        $user = Auth::user();

        // question id must belong to submission_id
        if ($submission->id != $question->submission_id) {
            return response(['errors' => ['answer' => ['Fall und Rückfrage passen nicht zusammen.']]], '400');
        }

        // question must be open
        if ($question->answered_at) {
            return response(['errors' => ['answer' => ['Sie haben diese Rückfrage bereits beantwortet.']]], '400');
        }

        // answer min length
        $answer = filter_var(trim($request->answer), FILTER_SANITIZE_STRING);
        if (strlen($answer) < 1) {
            return response(['errors' => ['answer' => ['Bitte beantworten sie die Frage.']]], '400');
        }

        $question->answer = $answer;
        $question->answered_at = Carbon::now();
        $question->save();

        // prolong due by responsetime (e.g. by 24 hours)
        $submission->due_at = Carbon::now()->addHours($submission->responsetime);
        // reset reminder_sent, so that the reminder will be sent again
        $submission->reminder_sent_at = null;
        $submission->save();

        event(new QuestionAnsweredEvent($question));

        return response(["success" => true]);
    }
}
