<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Events\DoctorRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\RegisterDoctor;
use App\Http\Requests\Doctor\SendResetLink;
use App\Http\Requests\Doctor\UpdatePassword;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\Location;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class DoctorController extends Controller
{
    public function register(RegisterDoctor $request): DoctorResource
    {
        $location = Location::create(
            [
                'name' => $request->input('marker_name'),
                'address' => $request->input('marker_address'),
                'lat' => $request->input('marker_lat'),
                'lng' => $request->input('marker_lng'),
                'type' => $request->input('marker_type'),
            ]
        );

        $doctor = new Doctor(
            [
                'prefix' => $request->prefix,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'description' => $request->description,
                'region_id' => $request->region_id,
                'location_id' => $location->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => false,
            ]
        );

        $doctor->uploadPhoto($request->file('photo'));

        $doctor->save();

        $doctor->languages()->attach($request->language_ids);

        event(new DoctorRegistered($doctor));

        return new DoctorResource($doctor);
    }

    public function sendResetLinkEmail(SendResetLink $request): void
    {
        $response = Password::broker('doctors')->sendResetLink($request->only('email'));

        if ($response !== Password::RESET_LINK_SENT) {
            abort(500, __('Something went wrong, please try again later'));
        }
    }

    public function login(Request $request)
    {
        $http = new Client;

        $response = $http->post('http://ohn/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    public function updatePassword(UpdatePassword $request)
    {
        $response = Password::broker('doctors')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();

                event(new PasswordReset($user));
            }
        );
    }
}