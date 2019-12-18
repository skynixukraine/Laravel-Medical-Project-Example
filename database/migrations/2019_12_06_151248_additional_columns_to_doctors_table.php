<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalColumnsToDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('email')->after('last_name');
            $table->boolean('is_active')->default(false)->after('description');
            $table->string('password')->after('email');
            $table->string('slug')->after('password');

            $table->unsignedInteger('location_id')->nullable()->change();

            $table->dropForeign('doctors_location_id_foreign');
            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('is_active');
            $table->dropColumn('password');
            $table->dropColumn('slug');

            $table->dropForeign('doctors_location_id_foreign');
            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('cascade');
        });
    }
}
