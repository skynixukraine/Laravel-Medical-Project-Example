<?php

namespace App\Nova;

use App\Nova\Filters\AnsweredBy;
use App\Nova\Filters\AnsweredFrom;
use App\Nova\Filters\AnsweredUntil;
use App\Nova\Filters\PartnerFilter;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Submission extends Resource
{

    public static $with = ['symptoms'];

    public static function label() {
        return 'Fälle';
    }
    public static function singularLabel() {
        return 'Fall';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Submission';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'description'
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
            ID::make()->sortable()
                ->onlyOnIndex(),

            new Panel('Partner', $this->platformFields()),
            new Panel('Fotos', $this->fotoFields()),
            new Panel('Patient', $this->patientFields()),
            new Panel('Befund', $this->befundFields()),
            new Panel('Befund (zusätzliche Angaben)', $this->additionalBefundFields()),
            new Panel('Beschreibung', $this->beschreibungFields()),
            new Panel('Bewertung', $this->feedbackFields()),
            new Panel('Sonstiges', $this->otherFields()),
        ];
    }

    protected function platformFields() {
        return [
            Text::make('Platform', 'partner_id')
                ->displayUsing(function ($value) {
                    $partner = \App\Models\Partner::find($value);
                    return $partner->partner_id;
                })
        ];
    }
    protected function fotoFields()
    {
        return [
            Avatar::make('Bereich', 'overview_image_id')
                ->disk('public')
                ->thumbnail(function () {
                    return $this->overview_image_id
                        ? '/api/submission/'.$this->id.'/photo/'.$this->overview_image_id.'/64'
                        : null;
                })
                ->preview(function () {
                    return $this->overview_image_id
                        ? '/api/submission/'.$this->id.'/photo/'.$this->overview_image_id.'/400'
                        : null;
                }),

            Avatar::make('Nahaufnahme 1', 'closeup_image_id')
                ->hideFromIndex()
                ->disk('public')
                ->thumbnail(function () {
                    return $this->closeup_image_id
                        ? '/api/submission/'.$this->id.'/photo/'.$this->closeup_image_id.'/64'
                        : null;
                })
                ->preview(function () {
                    return $this->closeup_image_id
                        ? '/api/submission/'.$this->id.'/photo/'.$this->closeup_image_id.'/400'
                        : null;
                }),

            Avatar::make('Nahaufnahme 2', 'closeup2_image_id')
                ->hideFromIndex()
                ->disk('public')
                ->thumbnail(function () {
                    return $this->closeup2_image_id
                        ? '/api/submission/'.$this->id.'/photo/'.$this->closeup2_image_id.'/64'
                        : null;
                })
                ->preview(function () {
                    return $this->closeup2_image_id
                        ? '/api/submission/'.$this->id.'/photo/'.$this->closeup2_image_id.'/400'
                        : null;
                }),

        ];
    }
    protected function patientFields()
    {

        return [
            Select::make('Herr/Frau', 'gender')->options([
                'm' => 'Herr',
                'f' => 'Frau'
            ])->displayUsingLabels()
                ->sortable(),

            Number::make('Alter', 'age')
                ->sortable(),

            Text::make('Email', 'email')
                ->hideFromIndex()
                ->sortable()

        ];
        }
    protected function befundFields()
    {
        return [
            DateTime::make('Eingereicht', 'created_at')
                ->format('DD.MM.YY, HH:mm')
                ->hideFromIndex()
                ->sortable(),

            DateTime::make('Beantwortet am', 'answered_at')
                ->format('DD.MM.YY, HH:mm')
                ->nullable()
                ->sortable(),

            Textarea::make('Befund', 'answer')
                ->rows(3)
                ->alwaysShow()
                ->hideFromIndex(),

            Text::make('Zugewiesen', 'assigned_to_user_id')
                ->displayUsing(function ($value) {
                    $user = \App\Models\User::find($value);
                    return $user
                        ? $user->name()
                        : '-';
                }),

            Status::make('Status', 'status')
                ->sortable()
                ->loadingWhen(['open', 'assigned', 'permanently_assigned'])
                ->failedWhen(['setup'])
        ];
    }
    protected function additionalBefundFields() {
        return [
            Text::make('Ferndiagnose möglich', 'diagnosis_possible')
                ->displayUsing(function ($value) {
                    if ($value === null) return "—";
                    return $value
                        ? "ja"
                        : "nein";
                })
                ->hideFromIndex(),

            Text::make('Diagnose', 'diagnosis')
                ->hideFromIndex(),

            Text::make('Patient muss in Klinik/Praxis', 'requires_doctors_visit')
                ->displayUsing(function ($value) {
                    if ($value === null) return "—";
                    return $value
                        ? "ja"
                        : "nein";
                })
                ->hideFromIndex(),

            Text::make('Medikament empfohlen?', 'did_recommend_medicine')
                ->displayUsing(function ($value) {
                    if ($value === null) return "—";
                    return $value
                        ? "ja"
                        : "nein";
                })
                ->hideFromIndex(),

            Text::make('Empfohlenes Medikament', 'recommended_medicine')
                ->hideFromIndex()

        ];
    }
    protected function beschreibungFields()
    {
        return [
            Text::make('Beschwerden', 'symptoms')
                ->displayUsing(function ($value) {
                    return implode(", ", $value->sortBy('order')->pluck('name')->toArray());
                })
                ->hideFromIndex(),

            Text::make('Beschwerden (sonstige)', 'other_symptoms')
                ->hideFromIndex(),

            Select::make('Einseitig/Beidseitig', 'side')
                ->options([
                    'einseitig' => 'einseitig',
                    'beidseitig' => 'beidseitig',
                    'nicht sicher' => 'nicht sicher'
                    ])
                ->displayUsingLabels()
                ->hideFromIndex(),

            Textarea::make('Betroffener Bereich', 'affected_area')
                ->rows(3)
                ->alwaysShow()
                ->hideFromIndex(),

            Text::make('Aufgetreten seit', 'since')
                ->hideFromIndex(),

            Text::make('Aufgetreten seit (andere Angabe)', 'since_other')
                ->hideFromIndex(),

            Text::make('Bisher behandelt', 'treated')
                ->displayUsing(function ($value) {
                    if ($value === null) return "—";
                    return $value
                        ? "ja"
                        : "nein";
                })
                ->hideFromIndex(),

            Text::make('Bisher behandelt (Medikament)', 'treatment')
                ->hideFromIndex(),

            Textarea::make('Weitere Informationen', 'description')
                ->rows(3)
                ->alwaysShow()
                ->hideFromIndex(),
        ];
    }
    protected function feedbackFields()
    {
        return [
            Number::make('Sterne', 'stars')
                ->sortable(),

            Text::make('Feedback', 'feedback')
                ->hideFromIndex(),
        ];
    }
    protected function otherFields() {
        return [
            Text::make('Platform', 'medium')
                ->sortable(),
            Text::make('Bearbeitungszeit', 'responsetime')
                ->resolveUsing(function ($responsetime) {
                    return ($responsetime) ? "maximal " . $responsetime . " Stunden" : "—";
                })
                ->hideFromIndex(),
            Text::make('Preis', 'amount')
                ->sortable()
                ->resolveUsing(function ($amount) {
                    return ($amount) ? $amount . " €" : "—";
                }),
            Text::make('Stripe Source ID', 'stripe_source_id')
                ->hideFromIndex(),
            Text::make('Stripe Payment Method', 'stripe_source_object')
                ->hideFromIndex(),
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
        return [
            new AnsweredBy,
            new AnsweredFrom,
            new AnsweredUntil,
            new PartnerFilter
        ];
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
        return [
            new DownloadExcel,
        ];
    }
}
