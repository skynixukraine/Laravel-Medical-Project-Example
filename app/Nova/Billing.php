<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Billing extends Resource
{
    public static $model = \App\Models\Billing::class;

    public static $search = ['id', 'amount'];

    public static function label(): string
    {
        return __('Billings');
    }

    public static function singularLabel(): string
    {
        return __('Billing');
    }

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            Currency::make('Amount', function () {
                return $this->amount / 100;
            })->format('%.2n'),

            Text::make(__('Currency'), 'currency')->sortable(),

            BelongsTo::make('Enquire', 'enquire')
        ];
    }
}
