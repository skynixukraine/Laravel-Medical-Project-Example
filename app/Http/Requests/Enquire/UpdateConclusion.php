<?php

declare(strict_types=1);

namespace App\Http\Requests\Enquire;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConclusion extends FormRequest
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
            'conclusion' => 'required|string|max:10000'
        ];
    }
}