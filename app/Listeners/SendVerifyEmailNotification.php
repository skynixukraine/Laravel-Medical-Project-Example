<?php

declare(strict_types=1);

namespace App\Listeners;

class SendVerifyEmailNotification
{
    public function handle($event): void
    {
        $event->getDoctor()->sendEmailVerificationNotification();
    }
}
