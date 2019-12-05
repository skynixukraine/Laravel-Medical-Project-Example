<?php

namespace App\Nova\Metrics;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Metrics\Value;

class SubsequentSubmissions extends Value
{

    public $name = "Nachträglich eingereicht";

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, Submission::where('status', '<>', 'setup')->where('cancel_email_sent_at', '<>', null));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            1 => '1 Tag',
            7 => '7 Tage',
            30 => '30 Tage',
            60 => '60 Tage',
            'MTD' => 'im Monat bis zum heutigen Tag',
            'QTD' => 'im Quartal bis zum heutigen Tag',
            'YTD' => 'im Jahr bis zum heutigen Tag',
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
        return 'subsequent-submissions';
    }
}
