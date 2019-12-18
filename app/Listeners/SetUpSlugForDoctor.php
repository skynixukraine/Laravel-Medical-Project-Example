<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DoctorSaving;
use App\Models\Doctor;
use Illuminate\Support\Str;

class SetUpSlugForDoctor
{
    public function handle(DoctorSaving $event): void
    {
        $doctor = $event->getDoctor();

        $initialSlug = Str::slug($doctor->prefix . '-' . $doctor->first_name . '-' . $doctor->last_name);

        if (!Doctor::whereSlug($initialSlug)->exists()) {
            $doctor->slug = $initialSlug;
            return;
        }

        $random = random_int(0, 9);

        while (Doctor::whereSlug($initialSlug . '-' . $random)->exists()) {
            $random .= random_int(0, 9);
        }

        $doctor->slug = $initialSlug . '-' . $random;
    }
}