<?php

namespace App\Nova\Components\Fields;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class PasswordWithoutHash extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'password-field';

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (! empty($request->{$requestAttribute})) {
            $model->{$attribute} = $request[$requestAttribute];
        }
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            ['value' => '']
        );
    }
}
