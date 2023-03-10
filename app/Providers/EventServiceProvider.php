<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\DoctorClosed::class => [
            \App\Listeners\SendDoctorAccountClosedNotification::class,
        ],

        \App\Events\DoctorDeleted::class => [
            \App\Listeners\SendDoctorAccountDeletedNotification::class,
        ],

        \App\Events\DoctorRequestedActivation::class => [
            \App\Listeners\SendDoctorRequestedActivationNotification::class,
            \App\Listeners\SendAdminRequestedActivationNotification::class,
        ],

        \App\Events\DoctorApproved::class => [
            \App\Listeners\SendDoctorApprovedNotification::class,
        ],

        \App\Events\DoctorActivated::class => [
            \App\Listeners\SendDoctorActivatedNotification::class,
        ],

        \App\Events\DoctorUnpaused::class => [
            \App\Listeners\SendDoctorUnpausedNotification::class,
        ],

        \App\Events\DoctorDeactivated::class => [
            \App\Listeners\SendDoctorDeactivatedNotification::class,
        ],

        \App\Events\MessageSaved::class => [
            \App\Listeners\SetSingleFirstMessage::class
        ],

        \App\Events\DoctorResettedPassword::class => [
            \App\Listeners\SendDoctorResettedPasswordNotification::class
        ],

        \App\Events\DoctorVerifiedEmail::class => [
            \App\Listeners\SendDoctorVerifiedEmailNotification::class
        ],

        \App\Events\EnquireMessageCreated::class => [
            \App\Listeners\UpdateLastContactedAtAndStatus::class,
            \App\Listeners\SendEnquireMessageCreatedNotification::class
        ],

        \App\Events\EnquireCreated::class => [
            \App\Listeners\SendEnquireCreatedNotification::class
        ],

        \App\Events\DoctorChangedEmail::class => [
            \App\Listeners\SendDoctorChangedEmailNotification::class
        ],

        \App\Events\DoctorUpdated::class => [
            \App\Listeners\SendDoctorPasswordChangedNotification::class
        ],

        \App\Events\DoctorCreated::class => [
            \App\Listeners\SendDoctorVerifyEmailNotification::class,
            \App\Listeners\SendAdminWhenDoctorVerifyEmailNotification::class
        ],

        \App\Events\EnquireUpdated::class => [
            \App\Listeners\SendEnquireClosedNotification::class
        ],

        \App\Events\ConclusionUpdated::class => [
            \App\Listeners\SendConclusionUpdatedNotification::class
        ],

        \App\Events\EnquireVerifiedEmail::class => [
            \App\Listeners\SendEnquireVerifiedEmailNotification::class
        ],

        \App\Events\EnquireCharge::class => [
            \App\Listeners\SendEnquireChargeNotification::class
        ],
        
        \App\Events\ContactCreated::class => [
            \App\Listeners\SendContactAdminNotification::class,
            \App\Listeners\SendContactUserNotification::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
