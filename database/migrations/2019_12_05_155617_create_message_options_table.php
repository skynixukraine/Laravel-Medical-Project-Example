<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('message_id');
            $table->string('value')->nullable();
            $table->unsignedInteger('next_message_id')->nullable();

            $table->foreign('next_message_id')
                ->references('id')
                ->on('messages')
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
        Schema::dropIfExists('message_options');
    }
}
