<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorResettedPassword;
use App\Notifications\DoctorResettedPassword as DoctorResettedPasswordNotification;

class SendDoctorResettedPasswordNotification
{
    public function handle(DoctorResettedPassword $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorResettedPasswordNotification($doctor));
    }
}
