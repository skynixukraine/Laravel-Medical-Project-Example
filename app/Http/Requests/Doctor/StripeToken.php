<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

class StripeToken
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['code' => 'required|string|max:255'];
    }
}