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
            ['enquire_charge_description' => 'Enquire payment'],
            ['enquire_admins_fee' => '15.15'],
            ['enquire_price_currency' => 'usd'],
            ['enquire_total_price' => '49.87'],
            ['stripe_client_id' => 'ca_GVeaDvdqFNbcuNimN3M9c7Z9SLVCfd1X'],
            ['stripe_secret_key' => 'sk_test_HmnLPA76TzcsQfNoLiKyvGdi000epjDg41'],
            ['display_enquire_price' => '49.99$'],
        ]);
    }
}
