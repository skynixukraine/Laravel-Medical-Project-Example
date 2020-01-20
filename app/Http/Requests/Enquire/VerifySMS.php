<?php

declare(strict_types=1);

namespace App\Http\Requests\Enquire;

use Illuminate\Foundation\Http\FormRequest;

class VerifySMS extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'verification_code' => 'required|string|max:255'
        ];
    }
}