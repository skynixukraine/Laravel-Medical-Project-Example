<?php

declare(strict_types=1);

namespace App\Http\Requests\Enquire;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class VerifySMS extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'verification_code' => 'required|string|max:255',
            'recaptcha' => ['required', 'string', new Recaptcha('verify_sms')],
        ];
    }

    public function messages(): array
    {
        return [
            'verification_code.required' => __('The verification code field is required'),
            'verification_code.string' => __('The verification code field must be a string'),
            'verification_code.max' => __('The verification code may not be greater than 255 characters'),
            'recaptcha.required' => __('The recaptcha code must be provided'),
            'recaptcha.string' => __('The recaptcha code must be a string'),
        ];
    }
}