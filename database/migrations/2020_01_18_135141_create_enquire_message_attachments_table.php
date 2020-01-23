<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquireMessageAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquire_message_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->unsignedInteger('enquire_message_id');
            $table->timestamps();

            $table->foreign('enquire_message_id')
                ->references('id')
                ->on('enquire_messages')
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
        Schema::dropIfExists('enquire_message_attachments');
    }
}
