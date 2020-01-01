<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorMessageOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_options', function (Blueprint $table) {
            $table->unsignedInteger('message_id')->change();
            $table->dropForeign('message_options_next_message_id_foreign');

            $table->foreign('next_message_id')
                ->references('id')
                ->on('messages')
                ->onDelete('set null');

            $table->foreign('message_id')
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
        Schema::table('message_options', function (Blueprint $table) {
            $table->dropForeign('message_options_next_message_id_foreign');
            $table->dropForeign('message_options_message_id_foreign');

            $table->foreign('next_message_id')
                ->references('id')
                ->on('messages')
                ->onDelete('cascade');
        });

        Schema::table('message_options', function (Blueprint $table) {
            $table->unsignedSmallInteger('message_id')->change();
        });
    }
}
