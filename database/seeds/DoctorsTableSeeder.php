<?php

declare(strict_types=1);

use App\Models\Doctor;
use App\Models\DoctorTitle;
use Illuminate\Database\Seeder;

class DoctorsTableSeeder extends Seeder
{
    public function run(): void
    {
        factory(App\Models\Doctor::class, 10)->create()->each(function (Doctor $doctor) {
            $doctor->forceFill([
                'email_verified_at' => $doctor->freshTimestamp(),
                'title_id' => DoctorTitle::inRandomOrder()->first(['id'])->id
            ])->saveOrFail();
        });
    }
}
