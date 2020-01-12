<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Update extends FormRequest
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
            'photo' => 'dimensions:min_width=256,min_height=256,max_width=540,max_height=540',
            'prefix' => 'string|max:255',
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'email' => ['email', Rule::unique('doctors')->ignore(Auth::id())],
            'description' => 'string|max:3000',
            'region_id' => 'exists:regions,id',
            'specialization_id' => 'exists:specializations,id',
            'old_password' => 'string|min:6|max:255|required_with:password',
            'password' => 'string|min:6|max:255|regex:/^.*(?=.{6,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|confirmed|required_with:old_password',
            'address' => 'string|max:255',
            'city' => 'string|max:255',
            'state' => 'string|max:255',
            'country' => 'string|max:255',
            'postal_code' => 'string|max:255',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'language_ids' => 'array',
            'language_ids.*' => 'distinct|exists:languages,id',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $doctor = $this->route('doctor');

            if ($this->has('old_password') && !Hash::check($this->old_password, $doctor->password)) {
                $validator->errors()->add('old_password', 'The old password is invalid.');
            }
        });
    }
}