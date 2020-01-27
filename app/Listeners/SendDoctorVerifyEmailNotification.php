<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorCreated;
use App\Notifications\DoctorVerifyEmail as DoctorVerifyEmailNotification;

class SendDoctorVerifyEmailNotification
{
    public function handle(DoctorCreated $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorVerifyEmailNotification());
    }
}