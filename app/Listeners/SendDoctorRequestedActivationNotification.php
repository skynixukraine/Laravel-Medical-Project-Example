<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorRequestedActivation;
use App\Notifications\DoctorRequestedActivationNotification;

class SendDoctorRequestedActivationNotification
{
    public function handle(DoctorRequestedActivation $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorRequestedActivationNotification($doctor));
    }
}
