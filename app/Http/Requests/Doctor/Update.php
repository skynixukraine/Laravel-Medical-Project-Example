<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
            'password' => 'string|min:6|max:255|confirmed',
            'address' => 'string|max:255',
            'city' => 'string|max:255',
            'state' => 'string|max:255',
            'country' => 'string|max:255',
            'postal_code' => 'integer',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'language_ids' => 'array',
            'language_ids.*' => 'distinct|exists:languages,id',
        ];
    }
}