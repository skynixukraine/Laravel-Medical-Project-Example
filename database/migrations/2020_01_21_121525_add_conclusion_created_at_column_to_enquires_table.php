<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConclusionCreatedAtColumnToEnquiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquires', function (Blueprint $table) {
            $table->dateTime('conclusion_created_at')->nullable();
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
            $table->dropColumn('conclusion_created_at');
        });
    }
}
