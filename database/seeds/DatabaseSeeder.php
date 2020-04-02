<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
         $this->call(DoctorTitlesTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(DoctorsTableSeeder::class);
         $this->call(LanguagesTableSeeder::class);
         $this->call(SettingsTableSeeder::class);
         $this->call(EnquiresTableSeeder::class);
    }
}
