<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorRequestedActivation;
use App\Notifications\AdminRequestedActivation as AdminRequestedActivationNotification;

class SendAdminRequestedActivationNotification
{
    public function handle(DoctorRequestedActivation $event): void
    {
        $doctor = $event->getDoctor();
        Notification::route('mail', config('mail.extra.admin.to.email'))
            ->notify(new AdminRequestedActivationNotification($doctor));
    }
}
