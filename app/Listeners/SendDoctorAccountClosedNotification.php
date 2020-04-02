<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorClosed;
use App\Notifications\DoctorClosed as DoctorClosedNotification;

class SendDoctorAccountClosedNotification
{
    public function handle(DoctorClosed $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorClosedNotification($doctor));
    }
}
