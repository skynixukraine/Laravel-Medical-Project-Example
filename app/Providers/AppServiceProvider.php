<?php

namespace App\Providers;

use Authy\AuthyApi;
use Illuminate\Support\ServiceProvider;

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
    }
}
