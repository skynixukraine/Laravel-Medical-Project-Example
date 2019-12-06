<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Events\DoctorRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctor;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\Location;

class DoctorController extends Controller
{
    public function register(StoreDoctor $request): DoctorResource
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
                'password' => bcrypt($request->password),
                'is_active' => false
            ]
        );

        $doctor->uploadPhoto($request->file('photo'));

        $doctor->save();

        $doctor->languages()->attach($request->language_ids);

        event(new DoctorRegistered($doctor));

        return new DoctorResource($doctor);
    }
}