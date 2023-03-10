<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
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
            'phone_number' => 'required|string|unique:doctors',
            'email' => 'required|email|unique:doctors',
            'medical_degree' => 'mimes:pdf,jpg,png,jpeg|max:50000',
            'board_certification' => 'mimes:pdf,jpg,png,jpeg|max:50000',
            'password' => 'required|string|min:6|max:100|regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*.])(?=\\S+$).*$/|confirmed',
            'accepted' => 'required|accepted',
            'lanr' => 'integer|digits_between:1,9',
            'recaptcha' => ['required', 'string', new Recaptcha('register_doctor')],
        ];
    }
}