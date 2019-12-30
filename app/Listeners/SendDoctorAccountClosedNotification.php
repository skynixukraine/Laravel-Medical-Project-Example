<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorClosedAccount;
use App\Notifications\DoctorClosedAccountNotification;

class SendDoctorAccountClosedNotification
{
    public function handle(DoctorClosedAccount $event): void
    {
        $doctor = $event->getDoctor();
        $doctor->notify(new DoctorClosedAccountNotification($doctor));
    }
}
