<?php

namespace App\Nova;

use App\Services\StorageService;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\PasswordConfirmation;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Doctor extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Doctor';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

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
            Avatar::make('Photo', 'photo')
                ->store(function (Request $request, $doctor) {
                    (new StorageService())->saveDoctorPhoto($doctor, $request->photo);
                    return ['photo' => $doctor->photo,];
                }),
            Text::make('Prefix', 'prefix')->sortable(),
            Text::make('First name', 'first_name')->sortable(),
            Text::make('Last name', 'last_name')->sortable(),
            Text::make('Email', 'email')->sortable(),
            Password::make('Password', 'password')->hideFromIndex(),
            PasswordConfirmation::make('Password Confirmation'),
            Textarea::make('Description', 'description')->hideFromIndex(),
            Boolean::make('Is active', 'is_active')->sortable(),
            DateTime::make('Created at', 'created_at')->hideFromIndex(),
            DateTime::make('Updated at', 'updated_at')->hideFromIndex(),
            HasOne::make('Region')->hideFromIndex(),
            HasMany::make('Languages')->hideFromIndex(),
            HasOne::make('Location')->hideFromIndex(),
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
