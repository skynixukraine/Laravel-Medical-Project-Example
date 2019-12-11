<?php

declare(strict_types=1);

namespace App\Http\Requests\Doctor;

use App\Models\Doctor;
use App\Rules\Recaptcha;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
{
    private $tokenRepository;

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null,
        TokenRepositoryInterface $tokenRepository
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->tokenRepository = $tokenRepository;
    }

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
        $email = $this->input('email');

        return [
            'email' => 'required|email|exists:doctors',
            'password' => 'required|string|min:6|max:255|confirmed',
            'token' => 'required|string',
            'recaptcha' => ['required', 'string', new Recaptcha('reset_password')],
        ];
    }
}