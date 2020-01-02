<?php

namespace App\Providers;

use App\Models\Doctor;
use App\Models\Enquire;
use App\Policies\DoctorPolicy;
use App\Policies\EnquirePolicy;
use App\Policies\SubmissionPolicy;
use App\Policies\UserPolicy;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Doctor::class => DoctorPolicy::class,
        Enquire::class => EnquirePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addWeek(2));
        Passport::refreshTokensExpireIn(Carbon::now()->addMonth(3));
    }
}
