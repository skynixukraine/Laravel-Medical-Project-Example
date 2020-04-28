<?php

declare(strict_types=1);

namespace App\Nova;

use App\Models\Message as MessageModel;
use App\Models\EnquireAnswer as EnquireAnswerModel;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage as BaseStorage;
use App\Facades\Storage;

class EnquireAnswer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = EnquireAnswerModel::class;

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = ['id'];

    public static function label(): string
    {
        return __('Enquire answers');
    }

    public static function singularLabel(): string
    {
        return __('Enquire answer');
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return 'Enquire answer ' . $this->id;
    }

    public static $defaultSort = [
        'message_id' => 'asc'
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (static::$defaultSort && empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            foreach (static::$defaultSort as $field => $order) {
                $query->orderBy($field, $order);
            }
        }

        return $query;
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

            BelongsTo::make(__('Enquire'), 'enquire', Enquire::class),

            BelongsTo::make(__('Message title'), 'message', Message::class, 'message')->sortable(),

            Text::make(__('Question'), 'message', Message::class)->displayUsing(function ($text) {
                return $text->content;
            })->asHtml(),

            $this->getComponent(__('Answer'), 'value'),
        ];
    }

    /**
     * @param string $name
     * @param string $attribute
     * @return $this|static
     */
    public function getComponent(string $name, string $attribute)
    {
        if (isset($this->message->type) && $this->message->type == MessageModel::TYPE_IMAGE) {

            return Text::make($name, $attribute)->displayUsing(function () {

                $html = '<img src="' . Storage::getEnquireImageBase64($this->value) . '"> ';
                return $html;
            })->asHtml();

        } elseif (isset($this->message->type) && $this->message->type == MessageModel::TYPE_SELECT) {
            
            return Text::make($name, $attribute)->withMeta(['value' => implode(', ', json_decode($this->value))]);
            
        } elseif (isset($this->message->type) && $this->message->type == MessageModel::TYPE_BODY_SELECT) {

            return Text::make($name, $attribute)->displayUsing(function () {

                $html = '<img width="150px" src="data: image/svg+xml;base64,' . \App\Facades\Svg::setColorNovaByIds('body-back-nova.svg', json_decode($this->value)) . '"> ';
                $html .= '<img width="150px" src="data: image/svg+xml;base64,' . \App\Facades\Svg::setColorNovaByIds('body-front-nova.svg', json_decode($this->value)) . '">';
                return $html;
            })->asHtml();

        }

        return Text::make($name, $attribute);
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
