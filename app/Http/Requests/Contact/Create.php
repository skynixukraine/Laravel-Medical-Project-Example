<?php

declare(strict_types=1);

namespace App\Http\Requests\Contact;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'name'  => 'required|string',
            'body'  => 'required|string'
        ];
    }
}