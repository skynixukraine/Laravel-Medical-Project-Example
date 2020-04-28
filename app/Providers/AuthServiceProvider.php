<?php

namespace App\Providers;

use App\Models\Billing;
use App\Models\Doctor;
use App\Models\Enquire;
use App\Models\EnquireAnswer;
use App\Models\Location;
use App\Models\Setting;
use App\Policies\BillingPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\EnquireAnswerPolicy;
use App\Policies\EnquirePolicy;
use App\Policies\LocationPolicy;
use App\Policies\SettingPolicy;
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
        Doctor::class => DoctorPolicy::class,
        Enquire::class => EnquirePolicy::class,
        Billing::class => BillingPolicy::class,
        Location::class => LocationPolicy::class,
        Setting::class => SettingPolicy::class,
        EnquireAnswer::class => EnquireAnswerPolicy::class,
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
