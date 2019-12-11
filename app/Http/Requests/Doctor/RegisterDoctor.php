<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class RegisterDoctor extends FormRequest
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
            'photo' => 'required|dimensions:min_width=256,min_height=256,max_width=540,max_height=540',
            'prefix' => 'required|string|max:10',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'email' => 'required|email|unique:doctors',
            'description' => 'required|string|max:3000',
            'region_id' => 'required|exists:regions,id',
            'password' => 'required|string|min:6|max:255|confirmed',
            'marker_name' => 'nullable|string|max:60',
            'marker_address' => 'required|string|max:80',
            'marker_lat' => 'nullable|numeric',
            'marker_lng' => 'nullable|numeric',
            'marker_type' => 'nullable|string|max:30',
            'language_ids' => 'required|array',
            'language_ids.*' => 'distinct|exists:languages,id',
//            'recaptcha' => ['required', 'string', new Recaptcha('register_doctor')],
        ];
    }
}