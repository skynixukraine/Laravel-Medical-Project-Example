<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use App\Http\Requests\LatLngRulesTrait;
use Illuminate\Foundation\Http\FormRequest;

class Index extends FormRequest
{

    use LatLngRulesTrait;

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
            'first_name'        => 'nullable|string',
            'last_name'         => 'nullable|string',
            'specialization_id' => 'nullable|integer||exists:specializations,id',
            'region_id'         => 'nullable|integer||exists:regions,id',
            'radius'            => 'nullable|integer',
            'lat'               => ['nullable', 'required_with:radius', $this->latRegexRule()],
            'lng'               => ['nullable', 'required_with:radius', $this->lngRegexRule()],
        ];
    }
}