<?php

namespace App\Observers;

use App\Models\Submission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubmissionObserver
{
    /**
     * Handle the submission "created" event.
     *
     * @param  \App\Models\Submission  $submission
     * @return void
     */
    public function created(Submission $submission)
    {
        //
    }

    /**
     * Handle the submission "updated" event.
     *
     * @param  \App\Models\Submission  $submission
     * @return void
     */
    public function updated(Submission $submission)
    {
        //
    }

    public function deleting(Submission $submission)
    {
    }

    /**
     * Handle the submission "deleted" event.
     *
     * @param  \App\Models\Submission  $submission
     * @return void
     */
    public function deleted(Submission $submission)
    {
        $submission->symptoms()->detach();
        $submission->questions()->delete();

        // if the submission setup was not completed, the pictures are still in the /uploads folder
        // for submissions which have been completely setup, those should be in the /submissions folder
        if ($submission->closeup_image_id) {
            if (Storage::exists('uploads/'     . $submission->closeup_image_id . '.jpg')) Storage::delete('uploads/'     . $submission->closeup_image_id . '.jpg');
            if (Storage::exists('submissions/' . $submission->closeup_image_id . '.jpg')) Storage::delete('submissions/' . $submission->closeup_image_id . '.jpg');
        }
        if ($submission->closeup2_image_id) {
            if (Storage::exists('uploads/'     . $submission->closeup2_image_id . '.jpg')) Storage::delete('uploads/'     . $submission->closeup2_image_id . '.jpg');
            if (Storage::exists('submissions/' . $submission->closeup2_image_id . '.jpg')) Storage::delete('submissions/' . $submission->closeup2_image_id . '.jpg');
        }
        if ($submission->overview_image_id) {
            if (Storage::exists('uploads/'     . $submission->overview_image_id . '.jpg')) Storage::delete('uploads/'     . $submission->overview_image_id . '.jpg');
            if (Storage::exists('submissions/' . $submission->overview_image_id . '.jpg')) Storage::delete('submissions/' . $submission->overview_image_id . '.jpg');
        }

        // we are currently not deleting any existing resized version of those images in /storage/app/cached !
    }

    /**
     * Handle the submission "restored" event.
     *
     * @param  \App\Models\Submission  $submission
     * @return void
     */
    public function restored(Submission $submission)
    {
        //
    }

    /**
     * Handle the submission "force deleted" event.
     *
     * @param  \App\Models\Submission  $submission
     * @return void
     */
    public function forceDeleted(Submission $submission)
    {
        //
    }
}
