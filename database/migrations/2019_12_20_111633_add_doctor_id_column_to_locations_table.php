<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoctorIdColumnToLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign('doctors_location_id_foreign');
            $table->dropColumn('location_id');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->unsignedInteger('doctor_id')->nullable(false);
            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
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
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign('locations_doctor_id_foreign');
            $table->dropColumn('doctor_id');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->unsignedInteger('location_id')->nullable(false);
            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('cascade');
        });
    }
}
