<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPricePolicyToPricingPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \Illuminate\Support\Facades\DB::table('pricing_policies')
            ->where('enquire_total_price', 1900)
            ->update([
                'invoice_1A_factor' => 100,
                'invoice_1A_price' => 466,
                'invoice_5A_factor' => 100,
                'invoice_5A_price' => 466,
                'invoice_75A_factor' => 42,
                'invoice_75A_price' => 318,
            ]);

        $data = [
            'enquire_admins_fee' => '750',
            'enquire_total_price' => '2500',
            'enquire_display_price' => '25.00',
            'currency' => 'eur',
            'description' => 'Plan (25)',
            'invoice_1A_factor' => 139,
            'invoice_1A_price' => 648,
            'invoice_5A_factor' => 139,
            'invoice_5A_price' => 648,
            'invoice_75A_factor' => 60,
            'invoice_75A_price' => 454,
        ];

        \Illuminate\Support\Facades\DB::table('pricing_policies')->insertGetId($data);

        $data = [
            'enquire_admins_fee' => '900',
            'enquire_total_price' => '3400',
            'enquire_display_price' => '34.00',
            'currency' => 'eur',
            'description' => 'Plan (34)',
            'invoice_1A_factor' => 200,
            'invoice_1A_price' => 932,
            'invoice_5A_factor' => 200,
            'invoice_5A_price' => 932,
            'invoice_75A_factor' => 84,
            'invoice_75A_price' => 636,
        ];

        \Illuminate\Support\Facades\DB::table('pricing_policies')->insertGetId($data);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
