<?php

namespace App\Providers;

use App\Models\Setting;
use Authy\AuthyApi;
use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('authy', function ($app) {
            return new AuthyApi(config('app.authy_secret'));
        });

        Stripe::setApiKey(config('app.stripe_secret_key'));
        Stripe::setClientId(config('app.stripe_client_id'));
    }
}
