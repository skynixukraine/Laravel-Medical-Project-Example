<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorApproved;
use App\Notifications\DoctorApproved as DoctorApprovedNotification;

class SendDoctorApprovedNotification
{
    public function handle(DoctorApproved $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorApprovedNotification($doctor));
    }
}
