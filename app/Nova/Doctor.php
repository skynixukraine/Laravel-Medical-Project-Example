<?php

declare(strict_types=1);

namespace App\Nova;

use App\Nova\Actions\ActivateDoctor;
use App\Nova\Actions\DeactivateDoctor;
use App\Services\StorageService;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\PasswordConfirmation;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;

class Doctor extends Resource
{

    public static function label(): string
    {
        return __('Doctors');
    }

    public static function singularLabel(): string
    {
        return __('Doctor');
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Doctor::class;

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
        'id', 'first_name', 'last_name',
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
        $storage = new StorageService();

        return [
            ID::make()->sortable(),

            Avatar::make(__('Photo'), 'photo')
                ->store(function (Request $request) use ($storage) {
                    return ['photo' => $storage->saveDoctorsPhoto($request->photo)];
                }
            )
            ->rules('dimensions:min_width=256,min_height=256,max_width=540,max_height=540'),

            File::make('Board certification', 'board_certification')
                ->store(function (Request $request) use ($storage) {
                    return ['board_certification' => $storage->saveDoctorsBoardCertification($request->board_certification)];
                })
            ->rules('mimetypes:image/jpeg,image/png,application/pdf|mimes:pdf,jpg,png,jpeg|max:50000'),

            File::make('Medical degree', 'medical_degree')
                ->store(function (Request $request) use ($storage) {
                    return ['medical_degree' => $storage->saveDoctorsMedicalDegree($request->medical_degree)];
                })
            ->rules('mimetypes:image/jpeg,image/png,application/pdf|mimes:pdf,jpg,png,jpeg|max:50000'),

            Text::make(__('Title'), 'title')->sortable()
                ->rules('string', 'max:255'),

            Text::make(__('First name'), 'first_name')->sortable()
                ->rules('string', 'max:255'),

            Text::make(__('Last name'), 'last_name')->sortable()
                ->rules('string', 'max:255'),

            Text::make(__('E-mail'), 'email')
                ->sortable()
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:doctors,email')
                ->updateRules('unique:doctors,email,{{resourceId}}'),

            Text::make(__('Phone number'), 'phone_number')
                ->sortable()
                ->rules('required', 'string', 'max:255')
                ->creationRules('unique:doctors,phone_number')
                ->updateRules('unique:doctors,phone_number,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6|max:255|regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|confirmed')
                ->updateRules('string|min:6|max:255|regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|confirmed'),

            PasswordConfirmation::make(__('Password confirmation')),

            Trix::make(__('Description'), 'description')->hideFromIndex(),

            Boolean::make(__('Is active'), 'is_active')->sortable(),

            DateTime::make(__('Created at'), 'created_at')->onlyOnDetail(),

            DateTime::make(__('Updated at'), 'updated_at')->onlyOnDetail(),

            DateTime::make(__('Email verified at'), 'updated_at')->onlyOnDetail(),

            BelongsTo::make(__('Region'), 'region', Region::class)->hideFromIndex()->creationRules('nullable'),

            HasOne::make(__('Location'), 'location', Location::class)->hideFromIndex(),

            BelongsToMany::make(__('Languages'), 'languages', Language::class)->hideFromIndex()
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
        return [new ActivateDoctor(), new DeactivateDoctor()];
    }
}
