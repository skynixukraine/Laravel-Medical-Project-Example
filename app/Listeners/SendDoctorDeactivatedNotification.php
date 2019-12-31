<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorDeactivated;
use App\Notifications\DoctorDeactivatedNotification;

class SendDoctorDeactivatedNotification
{
    public function handle(DoctorDeactivated $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorDeactivatedNotification($doctor));
    }
}
