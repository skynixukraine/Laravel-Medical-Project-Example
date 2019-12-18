<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('lat', 'latitude');
            $table->renameColumn('lng', 'longitude');
            $table->dropColumn('name');
            $table->dropColumn('type');

            $table->string('city');
            $table->string('state');
            $table->integer('postal_code');
            $table->string('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('postal_code');
            $table->dropColumn('country');

            $table->renameColumn('latitude', 'lat');
            $table->renameColumn('longitude', 'lng');
            $table->string('name');
            $table->string('type');
        });
    }
}
