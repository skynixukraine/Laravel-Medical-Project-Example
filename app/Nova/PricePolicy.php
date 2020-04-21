<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;

class PricePolicy extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PricePolicy::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'description';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'description'
    ];

    public static function label(): string
    {
        return __('Price Policy');
    }

    public static function singularLabel(): string
    {
        return __('Price Policy');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Enquire display price'), 'enquire_display_price')
                ->rules('required', 'string'),

            Text::make(__('Enquire total price'), 'enquire_total_price')->sortable()
                ->help('The price should be in cents!!!')
                ->rules('required', 'numeric'),

            Text::make(__('Enquire admins fee'), 'enquire_admins_fee')->sortable()
                ->help('The price should be in cents!!!')
                ->rules('required', 'numeric', 'lt:enquire_total_price'),

            Text::make(__('Currency'), 'currency')->sortable()
                ->help('Currency is string with a currency code, e.g. eur')
                ->rules('required', 'string'),

            Text::make(__('Description'), 'description')
                ->rules('nullable', 'max:255'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
