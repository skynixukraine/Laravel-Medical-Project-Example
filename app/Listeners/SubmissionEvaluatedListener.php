<?php

namespace App\Listeners;

use App\Events\SubmissionEvaluatedEvent;
use App\Notifications\SubmissionEvaluatedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SubmissionEvaluatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SubmissionEvaluatedEvent $event)
    {
        // send email to OHN
        try {
            Notification::route('mail', config('mail.cc.address'))
                ->notify(new SubmissionEvaluatedNotification($event->submission));
        }
        catch (\Exception $e) {
            report($e);
        }
    }
}
