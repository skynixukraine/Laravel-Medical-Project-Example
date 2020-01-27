<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class DoctorTitle extends Resource
{
    public static $model = \App\Models\DoctorTitle::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public static function label(): string
    {
        return __('Titles');
    }

    public static function singularLabel(): string
    {
        return __('Title');
    }

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->creationRules('unique:doctor_titles,name')
                ->updateRules('unique:doctor_titles,name,{{resourceId}}')
        ];
    }
}
