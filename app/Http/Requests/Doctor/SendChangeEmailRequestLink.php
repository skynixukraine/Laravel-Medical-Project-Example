<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use App\Models\Doctor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SendChangeEmailRequestLink extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user() instanceof Doctor;
    }

    public function rules(): array
    {
        return ['email' => 'required|email|unique:doctors,email'];
    }
}