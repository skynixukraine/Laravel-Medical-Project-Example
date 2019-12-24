<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorRegistered as DoctorRegisteredEvent;

class SendDoctorRegisteredNotification
{
    /**
     * Handle the event.
     *
     * @param  DoctorRegisteredEvent  $event
     * @return void
     */
    public function handle(DoctorRegisteredEvent $event): void
    {
        $event->getDoctor()->sendEmailVerificationNotification();
    }
}
