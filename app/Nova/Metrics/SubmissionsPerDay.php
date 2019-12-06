<?php

namespace App\Nova\Metrics;

use App\Models\Submission;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;

class SubmissionsPerDay extends Trend
{

    public $name = "Eingereichte Fälle pro Tag";

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->countByDays($request, Submission::where('status', '<>', 'setup'));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7 => '7 Tage',
            30 => '30 Tage',
            90 => '90 Days',
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'submissions-per-day';
    }
}
