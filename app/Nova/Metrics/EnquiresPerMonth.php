<?php

declare(strict_types=1);

namespace App\Nova\Metrics;

use App\Models\Enquire;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class EnquiresPerMonth extends Trend
{
    public function name()
    {
        return __('Enquires per month');
    }

    public function calculate(Request $request): TrendResult
    {
        return $this->countByMonths($request, Enquire::class);
    }

    public function ranges(): array
    {
        return [
            30 => __(':num months', ['num' => 12]),
            60 => __(':num months', ['num' => 24]),
            90 => __(':num months', ['num' => 36]),
        ];
    }

    public function uriKey()
    {
        return 'enquires-per-month';
    }
}
