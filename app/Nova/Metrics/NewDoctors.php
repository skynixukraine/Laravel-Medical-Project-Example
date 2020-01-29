<?php

declare(strict_types=1);

namespace App\Nova\Metrics;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\ValueResult;
use Laravel\Nova\Metrics\Value;

class NewDoctors extends Value
{
    public function name()
    {
        return __('New doctors');
    }

    public function calculate(Request $request): ValueResult
    {
        return $this->count($request, Doctor::class);
    }

    public function ranges(): array
    {
        return [
            1 => __(':num day', ['num' => 1]),
            7 => __(':num days', ['num' => 7]),
            30 => __(':num days', ['num' => 30]),
            60 => __(':num days', ['num' => 60]),
            90 => __(':num days', ['num' => 90]),
            365 => __(':num days', ['num' => 365]),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
        ];
    }

    public function uriKey()
    {
        return 'new-doctors';
    }
}
