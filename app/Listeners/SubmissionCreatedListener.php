<?php

namespace App\Listeners;

use App\Events\SubmissionCreatedEvent;
use App\Mail\NewSubmission;
use App\Notifications\SubmissionCreatedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SubmissionCreatedListener
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
     * @param  SubmissionCreatedEvent  $event
     * @return void
     */
    public function handle(SubmissionCreatedEvent $event)
    {
        $submission = $event->submission;

        // Mail to patient
        if ($submission->email) {
            try {
                Notification::route('mail', $submission->email)
                    ->notify(new SubmissionCreatedNotification($submission));
            }
            catch (\Exception $e) {
                report($e);
            }
        }

        // send seperate email to OHN with all confirmed doctors in bcc
        try {
            $doctors = User::where('status','confirmed')->pluck('email');
            Mail::to(config('mail.from.address'))
                ->bcc($doctors)
                ->send(new NewSubmission($submission));
        }
        catch (\Exception $e) {
            report($e);
        }
    }
}
