<?php

declare(strict_types=1);

namespace App\Http\Requests\Enquire;

use App\Models\Doctor;
use App\Models\Enquire;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Create extends FormRequest
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
            'phone_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required', Rule::in([Enquire::GENDER_MALE, Enquire::GENDER_FEMALE]),
            'date_of_birth' => 'required|date',
            'doctor_id' => 'required',
            Rule::exists('doctors', 'id')->where(static function (Builder $query) {
                $query->whereIn('status',
                    [Doctor::STATUS_CREATED, Doctor::STATUS_ACTIVATION_REQUESTED, Doctor::STATUS_ACTIVATED])
                    ->whereNotNull('email_verified_at');
            }),
            'address' => 'string|max:255',
            'city' => 'string|max:255',
            'state' => 'string|max:255',
            'country' => 'string|max:255',
            'postal_code' => 'string|max:255',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'answers' => 'required|array|min:1|max:1000'
        ];
    }
}