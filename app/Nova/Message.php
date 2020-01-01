<?php

declare(strict_types=1);

namespace App\Nova;

use App\Models\Message as MessageModel;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;

class Message extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = MessageModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

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
        return __('Messages');
    }

    public static function singularLabel(): string
    {
        return __('Message');
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

            Text::make(__('Title'), 'title')->sortable()->rules('required', 'max:255'),

            Trix::make(__('Content'), 'content')->hideFromIndex(),

            Text::make(__('Questioner'), 'questioner')->sortable()->rules('required', 'max:255'),

            Select::make(__('Type'), 'type')->sortable()->rules('required', 'max:255')->options([
                MessageModel::TYPE_SELECT => __('SELECT'),
                MessageModel::TYPE_RADIO => __('RADIO'),
                MessageModel::TYPE_TEXT => __('TEXT'),
                MessageModel::TYPE_BODY_SELECT => __('BODY SELECT'),
                MessageModel::TYPE_IMAGE => __('IMAGE'),
            ])->displayUsingLabels(),

            Text::make(__('Button'), 'button')->sortable()->rules('required', 'max:255'),

            Boolean::make(__('Is first'), 'is_first')->sortable()->rules('required', 'max:255'),

            BelongsTo::make(__('Next message'), 'next', __CLASS__)->sortable()->nullable(),

            $this->optionsField(),
        ];
    }

    private function optionsField()
    {
        $message = $this->model();
        return $this->mergeWhen(in_array($message->type, [MessageModel::TYPE_RADIO, MessageModel::TYPE_SELECT], true), [
            HasMany::make(__('Options'), 'options', MessageOption::class)->onlyOnDetail()
        ]);
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
