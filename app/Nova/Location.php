<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Text;

class Location extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Location::class;

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label(): string
    {
        return __('Locations');
    }

    public static function singularLabel(): string
    {
        return __('Location');
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->country . ', ' . $this->city . ', ' . $this->state . ', ' . $this->address . ', ' . $this->postal_code;
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

            Country::make(__('Country'), 'country')->sortable()
                ->rules('nullable', 'string', 'max:255'),

            Place::make(__('City'), 'city')->sortable()->onlyCities()
                ->rules('nullable', 'string', 'max:255'),

            Text::make(__('State'), 'state')->sortable()
                ->rules('nullable', 'string', 'max:255'),

            Place::make(__('Address'), 'address')->sortable()->countries(['DE'])
                ->rules('nullable', 'string', 'max:255'),

            Text::make(__('Postal code'), 'postal_code')->sortable()
                ->rules('nullable', 'string'),

            Text::make(__('Latitude'), 'latitude')->sortable()->hideFromIndex()
                ->rules('nullable', 'numeric'),

            Text::make(__('Longitude'), 'longitude')->sortable()->hideFromIndex()
                ->rules('nullable', 'numeric'),
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
