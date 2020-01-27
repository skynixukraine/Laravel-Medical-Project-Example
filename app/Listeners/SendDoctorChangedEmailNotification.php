<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorChangedEmail;
use App\Notifications\DoctorChangedEmail as DoctorChangedEmailNotification;

class SendDoctorChangedEmailNotification
{
    public function handle(DoctorChangedEmail $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorChangedEmailNotification());
    }
}
