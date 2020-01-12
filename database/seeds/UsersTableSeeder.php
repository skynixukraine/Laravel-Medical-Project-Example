<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class)->create([
            'first_name' => 'Mario',
            'last_name' => 'Mabuse',
            'email' => 'dr-mabuse@tee-online.de'
        ]);

        factory(App\Models\User::class, 6)->create();
    }
}
