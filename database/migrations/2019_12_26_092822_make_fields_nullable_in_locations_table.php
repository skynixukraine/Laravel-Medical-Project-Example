<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeFieldsNullableInLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('address')->nullable(true)->change();
            $table->string('city')->nullable(true)->change();
            $table->string('state')->nullable(true)->change();
            $table->string('country')->nullable(true)->change();
            $table->string('postal_code')->nullable(true)->change();
            $table->float('latitude', 10, 6)->nullable(true)->change();
            $table->float('longitude', 10, 6)->nullable(true)->change();
        });
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
