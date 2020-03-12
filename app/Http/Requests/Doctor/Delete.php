<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class Delete extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => 'required|string',
        ];
    }
}