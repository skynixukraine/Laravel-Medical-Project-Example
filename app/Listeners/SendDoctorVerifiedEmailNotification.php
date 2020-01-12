<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorVerifiedEmail;
use App\Notifications\DoctorVerifiedEmail as DoctorVerifiedEmailNotification;

class SendDoctorVerifiedEmailNotification
{
    public function handle(DoctorVerifiedEmail $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorVerifiedEmailNotification($doctor));
    }
}
