<?php

namespace App\Listeners;

use App\Events\SubmissionAnsweredEvent;
use App\Notifications\SubmissionAnsweredNotification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;

class SubmissionAnsweredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SubmissionAnsweredEvent $event
     * @return void
     */
    public function handle(SubmissionAnsweredEvent $event)
    {
        $submission = $event->submission;

        // Mail to patient
        if ($submission->email) {
            try {
                Notification::route('mail', $submission->email)
                    ->notify(new SubmissionAnsweredNotification($submission));
            }
            catch (\Exception $e) {
                report($e);
            }
        }
        if ($submission->device_id) {
            App::setLocale($submission->partner->language);
            $submission->sendPushMessage(__("case-submit.result_available"));
        }

    }
}
