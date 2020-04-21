<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Support\Facades\Notification;
use App\Events\DoctorCreated;
use App\Notifications\AdminDoctorsVerifyEmail as AdminDoctorsVerifyEmailNotification;

class SendAdminWhenDoctorVerifyEmailNotification
{
    public function handle(DoctorCreated $event): void
    {
        $doctor = $event->getDoctor();
        Notification::route('mail', config('mail.extra.admin.to.email'))
            ->notify(new AdminDoctorsVerifyEmailNotification($doctor));
    }
}