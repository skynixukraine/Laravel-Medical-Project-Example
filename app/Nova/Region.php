<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class Region extends Resource
{
    public static $model = \App\Models\Region::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public static function label(): string
    {
        return __('Regions');
    }

    public static function singularLabel(): string
    {
        return __('Region');
    }

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->creationRules('unique:regions,name')
                ->updateRules('unique:regions,name,{{resourceId}}')
        ];
    }
}
