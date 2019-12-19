<?php

namespace App\Nova;

use App\Services\StorageService;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\PasswordConfirmation;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;

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
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Avatar::make('Photo', 'photo')->store(
                function (Request $request, $doctor) {
                    (new StorageService())->saveDoctorPhoto($doctor, $request->photo);
                    return ['photo' => $doctor->photo];
                }
            )->rules('required', 'dimensions:min_width=256,min_height=256,max_width=540,max_height=540'),
            Text::make('Prefix', 'prefix')->sortable()->rules('required', 'string', 'max:10'),
            Text::make('First name', 'first_name')->sortable()->rules('required', 'max:255'),
            Text::make('Last name', 'last_name')->sortable()->rules('required', 'max:255'),
            Text::make('Email', 'email')->sortable()->rules('required', 'email', 'unique:doctors'),
            Text::make('Slug', 'slug')->onlyOnDetail(),
            Password::make('Password', 'password')->hideFromIndex()
                ->rules('required', 'string', 'min:6', 'max:255', 'confirmed'),
            PasswordConfirmation::make('Password Confirmation')->rules('required', 'max:255'),
            Trix::make('Description', 'description')->hideFromIndex()->rules('required'),
            Boolean::make('Is active', 'is_active')->sortable(),
            DateTime::make('Created at', 'created_at')->onlyOnDetail(),
            DateTime::make('Updated at', 'updated_at')->onlyOnDetail(),
            BelongsTo::make('Region')->hideFromIndex()->rules('required'),
            BelongsToMany::make('Languages')->hideFromIndex()->rules('required', 'max:255'),
            BelongsTo::make('Location')->hideFromIndex()->rules('required', 'max:255'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
