<?php

declare(strict_types=1);

namespace App\Nova;

use App\Nova\Actions\ActivateDoctor;
use App\Nova\Actions\ApproveDoctor;
use App\Nova\Actions\DeactivateDoctor;
use App\Services\StorageService;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphOne;
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
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'last_name', 'email', 'phone_number', 'title'
    ];

    public function title()
    {
        if ($this->first_name || $this->last_name) {
            return (($this->title . ' ') ?: '') . (($this->first_name . ' ') ?: '') . (($this->last_name . ' ') ?: '');
        }

        return $this->email;
    }

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
                ->store(function (Request $request, $doctor) use ($storage) {
                    $photo = $storage->saveDoctorsPhoto($request->photo);

                    if ($doctor->photo) {
                        $storage->removeFile($doctor->photo);
                    }

                    return ['photo' => $photo];
                }
            )
            ->rules('dimensions:min_width=256,min_height=256,max_width=540,max_height=540'),

            File::make('Board certification', 'board_certification')
                ->store(function (Request $request, $doctor) use ($storage) {
                    $boardCertification = $storage->saveDoctorsBoardCertification($request->board_certification);

                    if ($doctor->board_certification) {
                        $storage->removeFile($doctor->board_certification);
                    }

                    return ['board_certification' => $boardCertification];
                })
            ->rules('mimetypes:image/jpeg,image/png,application/pdf', 'mimes:pdf,jpg,png,jpeg', 'max:50000'),

            File::make('Medical degree', 'medical_degree')
                ->store(function (Request $request, $doctor) use ($storage) {
                    $medicalDegree = $storage->saveDoctorsMedicalDegree($request->medical_degree);

                    if ($doctor->medical_degree) {
                        $storage->removeFile($doctor->medical_degree);
                    }

                    return ['medical_degree' => $medicalDegree];
                })
            ->rules('mimetypes:image/jpeg,image/png,application/pdf', 'mimes:pdf,jpg,png,jpeg', 'max:50000'),

            Text::make(__('Title'), 'title')->sortable()
                ->rules('nullable', 'string', 'max:255'),

            Text::make(__('First name'), 'first_name')->sortable()
                ->rules('nullable', 'string', 'max:255'),

            Text::make(__('Last name'), 'last_name')->sortable()
                ->rules('nullable', 'string', 'max:255'),

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
                ->creationRules('required', 'string', 'min:6', 'max:255', 'regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/', 'confirmed')
                ->updateRules('nullable', 'string', 'min:6', 'max:255', 'regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/', 'confirmed'),

            PasswordConfirmation::make(__('Password confirmation')),

            Trix::make(__('Description'), 'description')->hideFromIndex(),

            Text::make('Status', 'status')->hideWhenCreating()->hideWhenUpdating()->sortable(),

            DateTime::make(__('Created at'), 'created_at')->onlyOnDetail(),

            DateTime::make(__('Updated at'), 'updated_at')->onlyOnDetail(),

            DateTime::make(__('Email verified at'), 'email_verified_at')->onlyOnDetail(),

            BelongsTo::make(__('Region'), 'region', Region::class)->hideFromIndex()->nullable(),

            BelongsTo::make(__('Specialization'), 'specialization', Specialization::class)->hideFromIndex()->nullable(),

            HasMany::make(__('Enquires'), 'enquires', Enquire::class)->hideFromIndex(),

            MorphOne::make(__('Location'), 'location', Location::class)->hideFromIndex(),

            BelongsToMany::make(__('Languages'), 'languages', Language::class)->hideFromIndex(),
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
        return [new ApproveDoctor()];
    }
}
