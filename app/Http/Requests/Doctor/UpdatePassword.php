<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use App\Models\Doctor;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class UpdatePassword extends FormRequest
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
            'password' => 'required|string|min:6|max:255|confirmed',
            'token' => 'required|string',
            'recaptcha' => ['required', 'string', new Recaptcha('reset_password')],
        ];
    }

    public function withValidator($validator)
    {
        $doctor = Doctor::where(['email' => $this->email])->first();
        $token = $this->token;

        $validator->after(function ($validator) use ($doctor, $token) {
            if (!Password::broker('doctors')->tokenExists($doctor, $token)) {
                $validator->errors()->add('token', 'Token does not exists');
            }
        });
    }
}