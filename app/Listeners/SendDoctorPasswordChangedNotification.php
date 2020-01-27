<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorUpdated;
use App\Notifications\DoctorPasswordChanged;

class SendDoctorPasswordChangedNotification
{
    public function handle(DoctorUpdated $event): void
    {
        $doctor = $event->getDoctor();

        if ($doctor->isDirty('password')) {
            $doctor->notify(new DoctorPasswordChanged());
        }
    }
}
