<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquireAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquire_availabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('enquire_id');
            $table->time('start_time');
            $table->time('end_time');

            $table->foreign('enquire_id')
                ->references('id')
                ->on('enquires')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquire_availabilities');
    }
}
