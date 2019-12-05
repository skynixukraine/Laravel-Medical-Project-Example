<?php

namespace App\Nova\Metrics;

use App\Models\Submission;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;

class SubmissionsPerOS extends Partition
{

    public $name = "Eingereichte Fälle nach Platform";

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, Submission::where('status', '<>', 'setup'), 'medium');
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
        return 'submissions-per-o-s';
    }
}
