<?php

namespace App\Providers;

use App\Nova\Metrics\CancelledSubmissions;
use App\Nova\Metrics\NewSubmissions;
use App\Nova\Metrics\SubmissionsPerDay;
use App\Nova\Metrics\SubmissionsPerOS;
use App\Nova\Metrics\SubsequentSubmissions;
use App\Observers\SubmissionObserver;
use App\Models\Submission;
use Laravel\Nova\Nova;
use Laravel\Nova\Cards\Help;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::serving(function () {
            Submission::observe(SubmissionObserver::class);
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->id, [
                2, // eisenfaust
                11, // matz
                14, // brinker
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new NewSubmissions,
            new SubmissionsPerDay,
            new CancelledSubmissions,
            new SubmissionsPerOS,
            new SubsequentSubmissions
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
