<?php

namespace App\Listeners;

use App\Events\QuestionAskedEvent;
use App\Notifications\QuestionAskedNotification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;

class QuestionAskedListener
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
     * @param QuestionAskedEvent $event
     * @return void
     */
    public function handle(QuestionAskedEvent $event)
    {
        $question = $event->question;
        $submission = $question->submission;

        // Mail to patient
        if ($submission->email) {
            try {
                Notification::route('mail', $submission->email)
                    ->notify(new QuestionAskedNotification($submission));
            }
            catch (\Exception $e) {
                report($e);
            }
        }
        if ($submission->device_id) {
            App::setLocale($submission->partner->language);
            $submission->sendPushMessage(__("case-submit.question_received"));
        }
    }
}
