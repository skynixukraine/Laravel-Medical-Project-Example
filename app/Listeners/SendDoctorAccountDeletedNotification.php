<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorDeleted;
use App\Notifications\DoctorDeleted as DoctorDeletedNotification;

class SendDoctorAccountDeletedNotification
{
    public function handle(DoctorDeleted $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorDeletedNotification($doctor));
    }
}
