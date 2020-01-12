<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['key' => 'enquire_charge_description', 'value' => 'Enquire payment'],
            ['key' => 'enquire_admins_fee', 'value' => '15.15'],
            ['key' => 'enquire_price_currency', 'value' => 'usd'],
            ['key' => 'enquire_total_price', 'value' => '49.87'],
            ['key' => 'stripe_client_id', 'value' => 'ca_GVeaDvdqFNbcuNimN3M9c7Z9SLVCfd1X'],
            ['key' => 'stripe_secret_key', 'value' => 'sk_test_HmnLPA76TzcsQfNoLiKyvGdi000epjDg41'],
            ['key' => 'display_enquire_price', 'value' => '49.99$'],
        ]);
    }
}
