<?php

declare(strict_types=1);

namespace App\Listeners;

class SendVerifyEmailNotification
{
    public function handle($event): void
    {
        $doctor = $event->getDoctor();

        if ($doctor->wasRecentlyCreated || $doctor->isDirty('email')) {
            $doctor->email_verified_at = null;
            $doctor->sendEmailVerificationNotification();
        }
    }
}
