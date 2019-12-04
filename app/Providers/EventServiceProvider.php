<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
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
