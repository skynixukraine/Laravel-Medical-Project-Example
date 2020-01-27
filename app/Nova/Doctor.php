<?php

declare(strict_types=1);

namespace App\Nova;

use App\Nova\Actions\ApproveDoctor;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
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

    public function title(): string
    {
        return !blank($this->first_name) || !blank($this->last_name)
            ? (($this->title . ' ') ?: '') . (($this->first_name . ' ') ?: '') . (($this->last_name . ' ') ?: '')
            : $this->email;
    }

    public static $model = \App\Models\Doctor::class;

    public static $search = [
        'id', 'title', 'first_name', 'last_name',
        'email', 'phone_number',
    ];

    public static function authorizable(): bool
    {
        return false;
    }

    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            Avatar::make(__('Photo'), 'photo')->store(function (Request $request) {
                return ['photo' => $request->photo];
            })->rules('mimes:jpg,png,jpeg', 'max:50000'),

            File::make('Board certification', 'board_certification')->store(function (Request $request) {
                return ['board_certification' => $request->board_certification];
            })->rules('mimes:pdf,jpg,png,jpeg', 'max:50000'),

            File::make('Medical degree', 'medical_degree')->store(function (Request $request) {
                return ['medical_degree' => $request->medical_degree];
            })->rules('mimes:pdf,jpg,png,jpeg', 'max:50000'),

            BelongsTo::make(__('Title'), 'title', DoctorTitle::class)->hideFromIndex()->nullable(),

            Text::make(__('First name'), 'first_name')->sortable()->rules('nullable', 'string', 'max:255'),

            Text::make(__('Last name'), 'last_name')->sortable()->rules('nullable', 'string', 'max:255'),

            Text::make(__('E-mail'), 'email')->sortable()->rules('required', 'email',
                    'max:255')->creationRules('unique:doctors,email')->updateRules('unique:doctors,email,{{resourceId}}'),

            Text::make(__('Phone number'), 'phone_number')->sortable()
                ->rules('required', 'string', 'max:255')
                ->creationRules('unique:doctors,phone_number')
                ->updateRules('unique:doctors,phone_number,{{resourceId}}'),

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

    public function actions(Request $request): array
    {
        return [new ApproveDoctor()];
    }
}
