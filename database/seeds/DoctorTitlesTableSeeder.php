<?php

declare(strict_types=1);

use App\Models\DoctorTitle;
use Illuminate\Database\Seeder;

class DoctorTitlesTableSeeder extends Seeder
{
    public function run()
    {
        factory(DoctorTitle::class, 2)->create();
    }
}
