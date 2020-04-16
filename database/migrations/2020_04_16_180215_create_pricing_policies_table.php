<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricingPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_policies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enquire_admins_fee');
            $table->integer('enquire_total_price');
            $table->integer('enquire_display_price');
            $table->integer('description');
            $table->string('currency', 20);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_policies');
    }
}
