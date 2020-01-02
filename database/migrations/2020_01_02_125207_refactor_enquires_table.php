<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorEnquiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquires', function (Blueprint $table) {
            $table->dropColumn('street');
            $table->dropColumn('postal_code');
            $table->dropColumn('city');
            $table->string('phone_number');
            $table->string('email');

            $table->unsignedInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
                ->onDelete('set null');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign('locations_doctor_id_foreign');
            $table->renameColumn('doctor_id', 'model_id');
            $table->string('model_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enquires', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('email');
            $table->string('postal_code');
            $table->string('city');
            $table->string('street');
            $table->dropForeign('enquires_doctor_id_foreign');
            $table->dropColumn('doctor_id');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->renameColumn('model_id', 'doctor_id');
            $table->dropColumn('model_type');
            $table->foreign('doctor_id')
                ->references('id')
                ->on('doctors')
                ->onDelete('cascade');
        });
    }
}
