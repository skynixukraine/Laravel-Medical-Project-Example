<?php

namespace App\Listeners;

use App\Events\QuestionAnsweredEvent;
use App\Notifications\QuestionAnsweredNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class QuestionAnsweredListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(QuestionAnsweredEvent $event)
    {
        $question = $event->question;
        $submission = $question->submission;

        // Mail to user (doctor)
        try {
            Notification::route('mail', $submission->assignedTo->email)
                ->notify(new QuestionAnsweredNotification($submission));
        }
        catch (\Exception $e) {
            report($e);
        }

        // send seperate email to OHN
        try {
            Notification::route('mail', config('mail.cc.address'))
                ->notify(new QuestionAnsweredNotification($submission));
        }
        catch (\Exception $e) {
            report($e);
        }


    }
}
