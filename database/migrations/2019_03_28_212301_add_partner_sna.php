<?php

use App\Models\Partner;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartnerSna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // We are adding both partners through this migration
        $sna = new Partner;
        $sna->name="snapdoc";
        $sna->partner_id="sna";
        $sna->mail_from_address="info@online-dermatologist.net";
        $sna->mail_from_name="Snapdoc";
        $sna->mail_cc_address="info@online-dermatologist.net";
        $sna->language='en';
        $sna->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Partner::where('partner_id', 'sna')->first()->delete();
    }
}
