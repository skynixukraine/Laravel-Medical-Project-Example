<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Doctor::class, 10)->create()->each(function (\App\Models\Doctor $doctor) {
            $doctor->markEmailAsVerified();
        });
    }
}
