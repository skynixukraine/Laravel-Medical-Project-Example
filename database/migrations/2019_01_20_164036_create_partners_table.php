<?php

use App\Partner;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('partner_id');
            $table->string('mail_from_address');
            $table->string('mail_from_name');
            $table->string('mail_cc_address');
            $table->timestamps();
        });

        // We are adding both partners through this migration
        $ohn = new Partner;
        $ohn->name="online-hautarzt.net";
        $ohn->partner_id="ohn";
        $ohn->mail_from_address="info@online-hautarzt.net";
        $ohn->mail_from_name="Online Hautarzt - Appdoc";
        $ohn->mail_cc_address="info@online-hautarzt.net";
        $ohn->save();

        $ohn = new Partner;
        $ohn->name="intimarzt.de";
        $ohn->partner_id="ita";
        $ohn->mail_from_address="info@intimarzt.de";
        $ohn->mail_from_name="Intimarzt.de";
        $ohn->mail_cc_address="info@intimarzt.net";
        $ohn->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
}
