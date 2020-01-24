<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorApproved;
use App\Notifications\DoctorActivated as DoctorActivatedNotification;

class SendDoctorActivatedNotification
{
    public function handle(DoctorApproved $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorActivatedNotification($doctor));
    }
}
