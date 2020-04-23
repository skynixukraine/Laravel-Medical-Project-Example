<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorUnpaused;
use App\Notifications\DoctorUnpaused as DoctorUnpausedNotification;

class SendDoctorUnpausedNotification
{
    public function handle(DoctorUnpaused $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorUnpausedNotification($doctor));
    }
}
