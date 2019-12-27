<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use App\Models\Doctor;
use App\Rules\Recaptcha;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Login extends FormRequest
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
            'email' => [
                'required',
                'string',
                'email',
                Rule::exists('doctors')->where(
                    static function (Builder $query) {
                        $query->whereIn('status', [Doctor::STATUS_CREATED, Doctor::STATUS_ACTIVATION_REQUESTED, Doctor::STATUS_ACTIVATED])
                            ->whereNotNull('email_verified_at');
                    }
                ),
            ],
            'password' => 'required|string',
            'recaptcha' => ['required', 'string', new Recaptcha('login_doctor')],
        ];
    }
}