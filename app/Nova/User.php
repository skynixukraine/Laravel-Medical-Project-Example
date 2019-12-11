<?php

namespace App\Nova;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;

class User extends Resource
{

    public static function label() {
        return 'Ärzte';
    }
    public static function singularLabel() {
        return 'Arzt';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\User';

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
        'id', 'title', 'first_name', 'last_name'
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
            new Panel('User details', $this->detailsFields()),
            new Panel('Zugangsdaten', $this->credentialFields()),
            new Panel('Address', $this->addressFields()),
            new Panel('Sonstiges', $this->otherFields())
            ];
    }

    protected function detailsFields() {

        if (App::environment() == "production") {
            $disk = 's3_public';
            $path = 'aerzte/';
        }
        else {
            $disk = 'public';
            $path = 'images/aerzte/';
        }

        return [
            ID::make()->sortable(),

            Avatar::make('Foto', 'photo')
                ->disk($disk)
                ->thumbnail(function () use ($disk, $path) {
                    return $this->photo
                        ? Storage::disk($disk)->url($path.$this->photo.'.jpg')
                        : Storage::disk($disk)->url($path.'no_photo.jpg');
                })
                ->preview(function () use ($disk, $path) {
                    return $this->photo
                        ? Storage::disk($disk)->url($path.$this->photo.'.jpg')
                        : Storage::disk($disk)->url($path.'no_photo.jpg');
                })
                ->hideWhenUpdating(),

            Text::make('Name', 'name')
                ->onlyOnIndex(),

            Select::make('Herr/Frau*', 'gender')->options([
                'm' => 'Herr',
                'f' => 'Frau'
            ])->displayUsingLabels()
            ->hideFromIndex()
            ->rules('required'),

            Text::make('Titel', 'title')
                ->hideFromIndex()
                ->rules('max:255'),

            Text::make('Vorname*', 'first_name')
                ->hideFromIndex()
                ->rules('required', 'max:255'),

            Text::make('Nachname*', 'last_name')
                ->hideFromIndex()
                ->rules('required', 'max:255')
        ];
    }
    protected function credentialFields() {
        return [
            Text::make('Email*', 'email')
                ->hideFromIndex()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->updateRules('nullable', 'string', 'min:6')
                ->help('Nur ausfüllen um ein neues Passwort zu vergeben.'),

            Status::make('Status', 'status')
                ->sortable()
                ->loadingWhen(['registered'])
                ->failedWhen(['blocked'])
                ->rules('required'),

            Select::make('Status*', 'status')->options([
                'registered' => 'registered',
                'confirmed' => 'confirmed',
                'blocked' => 'blocked'
            ])->onlyOnForms()
              ->rules('required'),

        ];
    }
    protected function addressFields() {

        return [
            Text::make('Strasse*', 'street')
                ->hideFromIndex()
                ->rules('required', 'max:255'),

            Text::make('PLZ*', 'zip')
                ->hideFromIndex()
                ->rules('required', 'max:255'),

            Text::make('Stadt*', 'city')
                ->hideFromIndex()
                ->rules('required', 'max:255'),

            Country::make('Land*', 'country')
                ->hideFromIndex()
                ->rules('required'),

            Text::make('Telefon*', 'phone')
                ->hideFromIndex()
                ->rules('required'),
        ];
    }
    protected function otherFields() {

        return [
            Date::make('Geburtsdatum', 'birthday')
                ->hideFromIndex()
                ->format('DD.MM.YYYY'),

            Text::make('Geburtsort', 'birthplace')
                ->hideFromIndex(),

            Number::make('Jahr der Facharztprüfung', 'graduation_year')
                ->hideFromIndex()
                ->min(1950)
                ->max(Carbon::today()->year),

            Textarea::make('Grund der Bewerbung*', 'reason_for_application')
                ->rows(3)
                ->alwaysShow()
                ->hideFromIndex()
                ->rules('required')
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
        return [
            new Lenses\MostAnsweredThisWeek,
            new Lenses\MostAnsweredThisMonth,
            new Lenses\MostAnsweredLastMonth,
            new Lenses\BestRated
        ];
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
