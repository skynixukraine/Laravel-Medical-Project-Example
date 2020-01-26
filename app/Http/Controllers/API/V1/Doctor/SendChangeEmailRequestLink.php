<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Doctor;

use App\Http\Controllers\API\V1\ApiController;
use App\Models\EmailVerifies;
use App\Notifications\DoctorVerifyChangedEmail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Doctor\SendChangeEmailRequestLink as SendChangeEmailRequestLinkRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SendChangeEmailRequestLink extends ApiController
{
    public function __invoke(SendChangeEmailRequestLinkRequest $request)
    {
        Auth::user()->emailVerify()->create([
            'email' => $request->email,
            'token' => Hash::make($token = Str::random(100)),
        ]);

        Auth::user()->notify(new DoctorVerifyChangedEmail($token));
    }
}