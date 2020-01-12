<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOldTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('submission_symptom');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('symptoms');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
