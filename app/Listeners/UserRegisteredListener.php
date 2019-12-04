<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use App\Notifications\RegistrationCompletedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class UserRegisteredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegisteredEvent  $event
     * @return void
     */
    public function handle(UserRegisteredEvent $event)
    {
        $user = $event->user;

        // Mail to registered doctor
        $user->notify(new RegistrationCompletedNotification($user));

        // Mail to OHN
        try {
            Notification::route('mail', config('mail.cc.address'))
                ->notify(new RegistrationCompletedNotification($user));
        }
        catch (\Exception $e) {
            report($e);
        }

    }
}
