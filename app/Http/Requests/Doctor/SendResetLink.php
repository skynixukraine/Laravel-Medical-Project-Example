<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class SendResetLink extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:doctors',
            'recaptcha' => ['required', 'string', new Recaptcha('send_reset_link')],
        ];
    }
}