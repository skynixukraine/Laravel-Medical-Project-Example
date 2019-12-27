<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    private $action;

    public function __construct(string $action)
    {
        $this->action = $action;
    }

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        $response = (new \ReCaptcha\ReCaptcha(config('app.RECAPTCHA_SECRET_KEY')))
            ->setExpectedAction($this->action)
            ->verify($value, request()->ip());

        return $response->isSuccess() && $response->getScore() > 0.6;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return trans('Recaptcha is invalid.');
    }
}