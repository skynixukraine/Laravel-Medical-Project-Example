<?php

use App\Models\EnquireMessage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquireMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquire_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->enum('sender', [EnquireMessage::SENDER_DOCTOR, EnquireMessage::SENDER_PATIENT]);
            $table->unsignedInteger('enquire_id')->nullable();
            $table->unsignedInteger('enquire_message_id')->nullable();
            $table->timestamps();

            $table->foreign('enquire_id')
                ->references('id')
                ->on('enquires')
                ->onDelete('SET NULL');

            $table->foreign('enquire_message_id')
                ->references('id')
                ->on('enquire_messages')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquire_messages');
    }
}
