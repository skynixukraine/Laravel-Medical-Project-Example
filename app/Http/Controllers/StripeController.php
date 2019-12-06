<?php

namespace App\Http\Controllers;

use App\Events\SubmissionCreatedEvent;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Charge;

class StripeController extends Controller
{
    // This is used to start a stripe Checkout Session for creditcard payments
    public function createCheckoutSession(Request $request) {

        // ----------
        // validation
        // ----------
        if (!$request->has('transaction_id') OR
            !$request->has('responsetime')) {
            return response(['errors' => ['creditcard' => ["Creditcard payment could not be initiated."]]], '400');
        }

        // identify the submission by transaction_id
        $submission = Submission::where('transaction_id', $request->transaction_id)->first();

        if (!$submission ||
            $submission->status != 'setup' ||
            !in_array($request->responsetime, $submission->validResponsetimes())) {
            return response(['errors' => ['creditcard' => ['Creditcard payment could not be initiated!']]], '400');
        }

        // update submission responsetime and amount
        $submission->responsetime = $request->responsetime;
        $submission->amount = $submission->getPrice($request->responsetime);
        $submission->save();

        // ---------------------------------
        // create stripe checkout session id
        // ---------------------------------
        Stripe::setApiKey(config('services.stripe.secret'));
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'name' => __("case-submit.product_title"),
                    'description' => __("case-submit.product_description", ['responsetime' => $request->responsetime]),
                    'images' => ['https://api.online-hautarzt.net/images/'.$submission->logo()],
                    'amount' => $submission->getPrice($request->responsetime)*100,
                    'currency' => 'eur',
                    'quantity' => 1,
                ]],
                'success_url' => self::urlCheckoutSession($submission, "success"),
                'cancel_url'  => self::urlCheckoutSession($submission, "cancel")
            ]);
        }
        catch (\Exception $e) {
            Log::info($e->getMessage());
            return response(['errors' => ['creditcard' => ['Creditcard payment failure']]], '500');
        }

        return ['session_id' => $session->id];
    }

    // returns a simple html page, which itself makes an ajax request "createCheckoutSession"
    // and then does the redirect to stripe
    // todo: remove code duplication with function createCheckoutSession
    public function appCheckout(Request $request) {

        if (!$request->has('transaction_id') OR
            !$request->has('responsetime')) {
            return response(['errors' => ['creditcard' => ["Creditcard payment could not be initiated."]]], '400');
        }

        // identify the submission by transaction_id
        $submission = Submission::where('transaction_id', $request->transaction_id)->first();

        if (!$submission ||
            $submission->status != 'setup' ||
            !in_array($request->responsetime, $submission->validResponsetimes())) {
            return response(['errors' => ['creditcard' => ['Creditcard payment could not be initiated!']]], '400');
        }

        return response()->view("app-checkout", [], 200);
    }

    public function authorizeSofort(Request $request) {
        return $this->processPayment($request, "sofort");
    }

    /**
     * The user was redirected from stripe checkout
     * Now we need to check if the payment was successful or canceled
     * (in web /api/stripe/checkcreditcardstate is used as the iframe src
     * in apps /api/stripe/checkcreditcardstate is used as the success / cancel url)
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkcreditcardstate(Request $request) {
        // todo: the errors responses should not be json (web and apps use a view for this)
        if (!$request->has('transaction_id') || (strlen($request->transaction_id) < 10)) {
            return response(['errors' => ['transaction_id' => ['invalid']]], '400');
        }

        $submission = Submission::where('transaction_id', $request->transaction_id)->first();
        if (!$submission) {
            return response(['errors' => ['transaction_id' => ['invalid transaction_id']]], '400');
        }
        if ($submission->status != "setup") {
            return response(['errors' => ['transaction_id' => ['case already paid']]], '400');
        }
        if ($request->state == "cancel") {
            if ($submission->medium == "web") {
                $view = 'case-submit-complete-'.$submission->partner->partner_id;
                $data = ["paymentError" => true,
                         "submission"   => $submission];
                return response()->view($view, $data, 200);
            }
            else {
                $data = ["transaction_status" => "failure",
                         "submission"         => $submission];
                return response()->view("app-checkout-complete", $data, 200);
            }
        }
        elseif ($request->state == "success") {

            if ($request->secret != $submission->stripeCheckoutSecret()) {
                return response(['errors' => ['secret' => ['invalid']]], '400');
            }

            $submission->stripe_source_object = "creditcard checkout";
            $submission->save();

            $this->setPaymentCompletedAndInformPatient($submission);

            // To which partner does this submission belong
            $partner_id = $submission->partner->partner_id;
            $view = 'case-submit-complete-'.$partner_id;

            if ($submission->medium == "web") {
                $data = ["transaction_status" => "successful",
                         "submission"         => $submission];
                return response()->view($view, $data, 200);
            }
            else {
                $data = ["transaction_status" => "successful",
                         "submission"         => $submission];
                return response()->view("app-checkout-complete", $data, 200);
            }
        }

        return response(['errors' => ['state' => ['invalid']]], '400');
    }

    private function processPayment ($request, $paymentMethod) {

        if ($request->has('transaction_id') && (strlen($request->transaction_id) > 10)) {
            $submission = Submission::where('transaction_id', $request->transaction_id)->first();
        }
        elseif ($request->has('submission_id') && (strlen($request->submission_id) > 10)) {
            $submission = Submission::where('submission_id', $request->submission_id)->first();
        }
        else {
            return response(['errors' => ['transaction_id' => ['missing']]], '400');
        }

        if (!$submission) {
            return response(['errors' => ['submission_id' => ['Fehler']]], '404');
        }
        if ($submission->status != "setup") {
            return response(['errors' => ['transaction_id' => ['case already paid']]], '400');
        }

        // To which partner does this submission belong
        $partner_id = $submission->partner->partner_id;
        $view = 'case-submit-complete-'.$partner_id;

        if ($partner_id == "ita")     $pricingTable = Submission::$pricingByResponsetimeITA;
        elseif ($partner_id == "sna") $pricingTable = Submission::$pricingByResponsetimeSNA;
        else                          $pricingTable = Submission::$pricingByResponsetimeOHN;
        $validResponsetimes = array_column($pricingTable, 'responsetime');
        $validationRules = [
            'livemode' => 'required',
            'source' => 'required',
            'client_secret' => 'required',
            'responsetime' => 'required|in:' . implode(",", $validResponsetimes)
        ];
        $request->validate($validationRules);

        $pricingTableKey = array_search($request->responsetime, array_column($pricingTable, 'responsetime'));
        $amount = $pricingTable[$pricingTableKey]["price"];

        // even if the payment fails, we can already update responsetime and amount of the submission
        $submission->responsetime = $request->responsetime;
        $submission->amount = $amount;
        $submission->stripe_source_object = $paymentMethod;
        $submission->save();

        $charge = $this->charge($amount * 100, $request->source, $submission->submission_id);
        if (!$charge) {
            if ($request->version == "web") return response()->view($view, ['paymentError' => "abgebrochen"] , 400);
            else                            return response(['errors' => [$paymentMethod => ["failed"]]], '400');
        }

        $expectedChargeStates = ["succeeded", "pending"];
        if ($this->wasChargeSuccessful($charge, $submission, $expectedChargeStates)) {

            // if charge was successful, we have a stripe_source_id, which can be saved
            $submission->stripe_source_id = $charge["source"]["id"];
            $submission->save();

            $this->setPaymentCompletedAndInformPatient($submission);

            if ($request->version == "web") {
                $data = ["transaction_status" => $charge["status"],
                         "submission"         => $submission];
                return response()->view($view, $data, 200);
            }
            else {
                $data = ["transaction_status" => $charge["status"],
                         "submission"         => ["submission_id" => $submission->submission_id]];
                return $data;
            }
        }
        else {
            if ($request->version == "web") return response()->view($view, ['paymentError' => 'unbekannter Fehler'], 400);
            else                            return response(['errors' => [$paymentMethod => ["Bezahlvorgang fehlgeschlagen"]]], '400');
        }
    }

    /**
     * @param $cents
     * @param $stripeToken
     * @param $submissionId
     * @return bool|\Stripe\ApiResource
     */
    private function charge($cents, $stripeToken, $submissionId) {

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $charge = Charge::create([
                'amount' => round($cents),
                'source' => $stripeToken,
                'currency' => 'eur',
                'metadata' => [
                    'submission' => substr($submissionId, 0, 5) . "..."
                ]
            ]);
            return $charge;
        }
        catch (\Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    private function wasChargeSuccessful($charge, $submission, $expectedChargeStates=["succeeded"]) {

        $successful = (isset($charge["status"]) &&
                       round($charge["amount"]) >= (($submission->amount * 100) - 1) && // we do dirty things to fix the rounding problem
                       $charge["currency"] == "eur" &&
                       in_array($charge["status"], $expectedChargeStates));
        return $successful;
    }

    private function setPaymentCompletedAndInformPatient($submission) {
        $submission->due_at = Carbon::now()->addHours($submission->responsetime);
        $submission->status = 'open';
        $submission->save();

        // fire an event, that can be used to send emails
        event(new SubmissionCreatedEvent($submission));
    }

    private static function urlCheckoutSession(Submission $submission, $state) {

        $params['transaction_id'] = $submission->transaction_id;
        $params['state']          = $state;
        if ($state == "success") {
            $params['secret'] = $submission->stripeCheckoutSecret();
        }
        $queryString = http_build_query($params);

        // web
        if ($submission->medium == "web") {
            if     ($submission->partner->partner_id == "ita") $url = config('app.ita.MIX_WEB_URL');
            elseif ($submission->partner->partner_id == "sna") $url = config('app.sna.MIX_WEB_URL');
            else                                               $url = config('app.ohn.MIX_WEB_URL');
            $url .= "/creditcard?".$queryString;
        }
        // ios and android
        else  {
            $url = route('checkcreditcardstate')."?".$queryString;
        }

        return $url;
    }


}
