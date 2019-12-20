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
        DB::table('users')->truncate();
        DB::table('sessions')->truncate();
        DB::table('password_resets')->truncate();

        factory(App\Models\User::class)->create(
            [
                'first_name' => 'Mario',
                'last_name' => 'Mabuse',
                'email' => 'dr-mabuse@tee-online.de'
            ]
        );


        // plus 6 more users (doctors)
        factory(App\Models\User::class, 6)->create();
    }
}
