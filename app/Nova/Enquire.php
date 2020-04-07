<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;

class Enquire extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Enquire::class;

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = true;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ['id', 'first_name', 'last_name', 'email', 'phone_number'];

    public static function label(): string
    {
        return __('Enquires');
    }

    public static function singularLabel(): string
    {
        return __('Enquire');
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->first_name . ' ' . $this->last_name;
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

            Text::make(__('First name'), 'first_name')->sortable(),

            Text::make(__('Last name'), 'last_name')->sortable(),

            Text::make(__('E-mail'), 'email')->sortable(),

            Text::make(__('Phone number'), 'phone_number')->sortable(),

            Select::make(__('Gender'), 'gender')->sortable()->options([
                \App\Models\Enquire::GENDER_MALE => __('Male'),
                \App\Models\Enquire::GENDER_FEMALE => __('Female'),
            ]),

            Select::make(__('Status'), 'status')->sortable()->displayUsingLabels()->options([
                \App\Models\Enquire::STATUS_NEW => __('New'),
                \App\Models\Enquire::STATUS_WAIT_PATIENT_RESPONSE => __('Awaiting for patient response'),
                \App\Models\Enquire::STATUS_WAIT_DOCTOR_RESPONSE => __('Awaiting for doctor response'),
                \App\Models\Enquire::STATUS_RESOLVED => __('Resolved'),
                \App\Models\Enquire::STATUS_ARCHIVED => __('Archived'),
            ]),

            Select::make(__('Payment'), 'payment_status')->sortable()->displayUsingLabels()->options([
                \App\Models\Enquire::PAYMENT_STATUS_PAID => __('Paid'),
                \App\Models\Enquire::PAYMENT_STATUS_FAIL => __('Fail'),
                \App\Models\Enquire::PAYMENT_STATUS_PENDING => __('Pending'),
            ]),

            BelongsTo::make(__('Doctor'), 'doctor', Doctor::class)->withMeta($this->doctor_id ? [] : ['value' => $this->doctor_info]),

            Date::make(__('Date of birth'), 'date_of_birth'),

            DateTime::make(__('Created at'), 'created_at')->onlyOnDetail(),

            DateTime::make(__('Updated at'), 'updated_at')->onlyOnDetail(),

            HasOne::make(__('Billing'), 'billing', Billing::class)->onlyOnDetail(),

            MorphOne::make(__('Location'), 'location', Location::class)->onlyOnDetail(),
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
