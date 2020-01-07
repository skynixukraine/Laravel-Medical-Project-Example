<?php

namespace App\Providers;

use App\Events\MessageSaved;
use App\Listeners\SetSingleFirstMessage;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SubmissionCreatedEvent' => [
            'App\Listeners\SubmissionCreatedListener',
        ],
        'App\Events\SubmissionAnsweredEvent' => [
            'App\Listeners\SubmissionAnsweredListener',
        ],
        'App\Events\SubmissionEvaluatedEvent' => [
            'App\Listeners\SubmissionEvaluatedListener',
        ],
        'App\Events\UserRegisteredEvent' => [
            'App\Listeners\UserRegisteredListener'
        ],
        'App\Events\QuestionAskedEvent' => [
            'App\Listeners\QuestionAskedListener'
        ],
        'App\Events\QuestionAnsweredEvent' => [
            'App\Listeners\QuestionAnsweredListener'
        ],
        'Laravel\Passport\Events\AccessTokenCreated' => [
            'App\Listeners\DeleteOldTokens',
        ],
        \App\Events\DoctorSaving::class => [
            \App\Listeners\SendVerifyEmailNotification::class,
        ],
        \App\Events\DoctorClosedAccount::class => [
            \App\Listeners\SendDoctorAccountClosedNotification::class,
        ],
        \App\Events\DoctorRequestedActivation::class => [
            \App\Listeners\SendDoctorRequestedActivationNotification::class,
        ],
        \App\Events\DoctorActivated::class => [
            \App\Listeners\SendDoctorActivatedNotification::class,
        ],
        \App\Events\DoctorDeactivated::class => [
            \App\Listeners\SendDoctorDeactivatedNotification::class,
        ],

        \App\Events\MessageSaved::class => [
            \App\Listeners\SetSingleFirstMessage::class
        ]
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
