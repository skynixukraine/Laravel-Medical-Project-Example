<?php

declare(strict_types=1);

namespace App\Nova;

use App\Facades\Storage;
use App\Models\Doctor as DoctorModel;
use App\Nova\Actions\ActivateDoctor;
use App\Nova\Actions\ApproveDoctor;
use App\Nova\Actions\DeactivateDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as BaseStorage;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Password;
use App\Nova\Components\Fields\PasswordWithoutHash;
use Laravel\Nova\Fields\PasswordConfirmation;

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
            ? ($this->title ? ($this->title->name . ' ') : '') . (($this->first_name . ' ') ?: '') . (($this->last_name . ' ') ?: '')
            : $this->email;
    }

    public static $model = DoctorModel::class;

    public static $search = [
        'id', 'first_name', 'last_name',
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

            Avatar::make(
                __('Photo'),
                'photo',
                config('filesystems.default'),
                function (Request $request) {
                    return ['photo' => $request->photo];
                }
            )->thumbnail(
                function ($value, $disk) {
                    return $value ? BaseStorage::disk($disk)->temporaryUrl($value, now()->addMinutes(5)) : null;
                }
            )->preview(
                function ($value, $disk) {
                    return $value ? BaseStorage::disk($disk)->temporaryUrl($value, now()->addMinutes(5)) : null;
                }
            )->rules('mimes:jpg,png,jpeg', 'max:50000'),

            $this->encryptedFileField(__('Board certification'), 'board_certification')
                ->rules('mimes:pdf,jpg,png,jpeg', 'max:50000'),

            $this->encryptedFileField(__('Medical degree'), 'medical_degree')
                ->rules('mimes:pdf,jpg,png,jpeg', 'max:50000'),

            BelongsTo::make(__('Title'), 'title', DoctorTitle::class)->hideFromIndex()->nullable(),

            Text::make(__('First name'), 'first_name')->sortable()->rules('nullable', 'string', 'max:255'),

            Text::make(__('Last name'), 'last_name')->sortable()->rules('nullable', 'string', 'max:255'),

            Text::make(__('E-mail'), 'email')->sortable()->rules('required', 'email',
                    'max:255')->creationRules('unique:doctors,email')->updateRules('unique:doctors,email,{{resourceId}}'),

            Text::make(__('Phone number'), 'phone_number')->sortable()
                ->rules('required', 'string', 'max:255')
                ->creationRules('unique:doctors,phone_number')
                ->updateRules('unique:doctors,phone_number,{{resourceId}}'),

            PasswordWithoutHash::make(__('Password'), 'password')
                ->hideFromIndex()
                ->hideFromDetail()
                ->creationRules('required', 'string', 'min:6', 'max:255', 'regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*.])(?=\\S+$).*$/', 'confirmed')
                ->updateRules('nullable', 'string', 'min:6', 'max:255', 'regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*.])(?=\\S+$).*$/', 'confirmed'),

            PasswordConfirmation::make(__('Password confirmation'), 'password_confirmation')
                ->hideFromIndex()
                ->hideFromDetail()
                ->creationRules('required', 'required_with:password', 'string', 'min:6')
                ->updateRules('nullable', 'required_with:password', 'string', 'min:6'),

            Textarea::make(__('Short description'), 'short_description')->hideFromIndex()
                ->rules('max:176'),

            Text::make(__('Lanr'), 'lanr')->hideFromIndex()
                ->rules('nullable', 'integer', 'digits_between:1,9'),

            Select::make(__('Status'), 'status')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->displayUsingLabels()
                ->sortable()
                ->options([
                    DoctorModel::STATUS_CREATED => ucfirst(strtolower(__(DoctorModel::STATUS_CREATED))),
                    DoctorModel::STATUS_ACTIVATION_REQUESTED => ucfirst(strtolower(__(DoctorModel::STATUS_ACTIVATION_REQUESTED))),
                    DoctorModel::STATUS_APPROVED => ucfirst(strtolower(__(DoctorModel::STATUS_APPROVED))),
                    DoctorModel::STATUS_ACTIVATED => ucfirst(strtolower(__(DoctorModel::STATUS_ACTIVATED))),
                    DoctorModel::STATUS_DEACTIVATED => ucfirst(strtolower(__(DoctorModel::STATUS_DEACTIVATED))),
                    DoctorModel::STATUS_CLOSED => ucfirst(strtolower(__(DoctorModel::STATUS_CLOSED))),
                ]),

            DateTime::make(__('Created at'), 'created_at')->onlyOnDetail(),

            DateTime::make(__('Updated at'), 'updated_at')->onlyOnDetail(),

            DateTime::make(__('Email verified at'), 'email_verified_at')->onlyOnDetail(),

            BelongsTo::make(__('Region'), 'region', Region::class)->hideFromIndex()->nullable(),

            BelongsTo::make(__('Price Policy'), 'pricePolicy', PricePolicy::class)->hideFromIndex()->nullable(),

            BelongsTo::make(__('Specialization'), 'specialization', Specialization::class)->hideFromIndex()->nullable(),

            HasMany::make(__('Enquires'), 'enquires', Enquire::class)->hideFromIndex(),

            MorphOne::make(__('Location'), 'location', Location::class)->hideFromIndex(),

            BelongsToMany::make(__('Languages'), 'languages', Language::class)->hideFromIndex(),
        ];
    }

    public function encryptedFileField(string $name, string $attribute)
    {
        $content = $this->{$attribute} ? Storage::getDecryptedContent($this->{$attribute}) : null;
        $fileName = $this->{$attribute} ? $attribute . '.' . Storage::guessContentExtension($content) : null;

        return File::make(
            $name,
            $attribute,
            config('filesystems.default'),
            function (Request $request) use ($attribute) {
                return [$attribute => $request->{$attribute}];
            }
        )->download(
            function ($request, $doctor) use ($content, $fileName) {
                return response()->streamDownload(function () use ($content) {echo $content;}, $fileName);
            }
        )->resolveUsing(
            function ($value, $resource) use ($fileName, $attribute) {
                return $this->{$attribute} ? $fileName : null;
            }
        );
    }

    public function actions(Request $request): array
    {
        return [
            new ApproveDoctor(),
            new ActivateDoctor(),
            new DeactivateDoctor()
        ];
    }
}
