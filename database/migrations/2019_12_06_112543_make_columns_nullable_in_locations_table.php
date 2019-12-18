<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeColumnsNullableInLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('name', 60)->nullable(true)->change();
            $table->float('lat', 10, 6)->nullable(true)->change();
            $table->float('lng', 10, 6)->nullable(true)->change();
            $table->string('type', 30)->nullable(true)->change();
            $table->string('address')->change();
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
            $table->string('name', 60)->nullable(false)->change();
            $table->float('lat', 10, 6)->nullable(false)->change();
            $table->float('lng', 10, 6)->nullable(false)->change();
            $table->string('type', 30)->nullable(false)->change();
            $table->string('address', 80)->change();
        });
    }
}
