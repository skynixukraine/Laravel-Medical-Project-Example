<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquireAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquire_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('enquire_id');
            $table->unsignedInteger('message_id');
            $table->unsignedInteger('message_option_id')->nullable();
            $table->string('value')->nullable();

            $table->foreign('enquire_id')
                ->references('id')
                ->on('enquires')
                ->onDelete('cascade');

            $table->foreign('message_id')
                ->references('id')
                ->on('messages')
                ->onDelete('cascade');

            $table->foreign('message_option_id')
                ->references('id')
                ->on('message_options')
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
        Schema::dropIfExists('enquire_answers');
    }
}
