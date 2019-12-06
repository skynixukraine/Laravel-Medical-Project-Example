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

        factory(App\Models\User::class)->create(['gender' => 'm', 'title' => 'Dr.',         'first_name' => 'Mario',    'last_name' => 'Mabuse',        'email' => 'dr-mabuse@tee-online.de',       'user_id' => 'aaaaaaaaa1', 'photo' => 'aaaaaaaaa1']);
        factory(App\Models\User::class)->create(['gender' => 'm', 'title' => 'Prof. Dr.',   'first_name' => 'Emil',     'last_name' => 'Eisenfaust',    'email' => 'dr-eisenfaust@tee-online.de',   'user_id' => 'aaaaaaaaa2', 'photo' => 'aaaaaaaaa2']);
        factory(App\Models\User::class)->create(['gender' => 'm', 'title' => 'Dr.',         'first_name' => 'Norbert',  'last_name' => 'No',            'email' => 'dr-no@tee-online.de',           'user_id' => 'aaaaaaaaa3', 'photo' => 'aaaaaaaaa3']);
        factory(App\Models\User::class)->create(['gender' => 'm', 'title' => '',            'first_name' => 'Olaf',     'last_name' => 'Oberschlau',    'email' => 'olaf-oberschlau@tee-online.de', 'user_id' => 'aaaaaaaaa4', 'photo' => 'aaaaaaaaa4']);

        // plus 6 more users (doctors)
        factory(App\Models\User::class, 6)->create();
    }
}
