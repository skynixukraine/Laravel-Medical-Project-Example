<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorDeleted;
use App\Notifications\DoctorDeleted as DoctorDeletedNotification;
use Illuminate\Support\Facades\Notification;

class SendDoctorAccountDeletedNotification
{
    public function handle(DoctorDeleted $event): void
    {
        $doctor = $event->getDoctor();

        Notification::route('mail', 'alexey.vnu+test@gmail.com')
                    ->notify(new DoctorDeletedNotification($doctor));
    }
}
