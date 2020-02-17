<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class EnquiresTableSeeder extends Seeder
{
    public function run(): void
    {
        factory(\App\Models\Enquire::class, 10)->create();
    }
}
