<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquireTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquire_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token');
            $table->unsignedInteger('enquire_id');
            $table->timestamps();
            $table->dateTime('expires_at');

            $table->foreign('enquire_id')
                ->references('id')
                ->on('enquires')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquire_tokens');
    }
}
