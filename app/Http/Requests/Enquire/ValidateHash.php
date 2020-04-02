<?php

declare(strict_types=1);

namespace App\Http\Requests\Enquire;

use Illuminate\Foundation\Http\FormRequest;

class ValidateHash extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hash' => 'required|string|max:255',
        ];
    }
}