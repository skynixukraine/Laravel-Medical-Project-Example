<?php

declare(strict_types=1);

namespace App\Nova\Metrics;

use App\Models\Enquire;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class EnquiresPerDay extends Trend
{
    public function name()
    {
        return __('Enquires per day');
    }

    public function calculate(Request $request): TrendResult
    {
        return $this->countByDays($request, Enquire::class);
    }

    public function ranges(): array
    {
        return [
            30 => __(':num days', ['num' => 30]),
            60 => __(':num days', ['num' => 60]),
            90 => __(':num days', ['num' => 90]),
        ];
    }

    public function uriKey()
    {
        return 'enquires-per-day';
    }
}
