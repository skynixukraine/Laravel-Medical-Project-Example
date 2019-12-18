<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorRegistered as DoctorRegisteredEvent;
use App\Notifications\DoctorRegistered as DoctorRegisteredNotification;

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
        $doctor = $event->getDoctor();

        $doctor->notify(new DoctorRegisteredNotification($event->getDoctor()));
    }
}
