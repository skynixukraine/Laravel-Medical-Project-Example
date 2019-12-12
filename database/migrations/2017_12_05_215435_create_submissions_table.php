<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Every submission has 2 pictures (overview and closeup)
        // But because there must be 2 pictures we don´t need to store any information in the database
        Schema::create('submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->string('since');
            $table->unsignedInteger('responsetime')->nullable(); // in hours (8 / 24 / 48). We need to allow null, because this is set in a second step
            $table->enum('gender', ['m', 'f']);
            $table->unsignedInteger('age');
            $table->string('city');
            $table->string('country');
            $table->string('email')->nullable();
            $table->string('device_id')->nullable();
            $table->string('submission_id');
            $table->string('transaction_id')->nullable();
            $table->timestamp('due_at')->nullable(); // We need to allow null, because this is set in a second step
            $table->enum('status', ['setup', 'open', 'assigned', 'permanently_assigned', 'answered']);
            $table->string('closeup_image_id')->nullable();
            $table->string('overview_image_id')->nullable();
            $table->unsignedInteger('assigned_to_user_id')->nullable(); // todo: this needs to be set, when the doctor clicks on "I want to answer this case"
            $table->timestamp('assigned_at')->nullable(); // todo: this needs to be set, when the doctor clicks on "I want to answer this case"
            $table->string('medium');

            $table->text('answer')->nullable();
            $table->timestamp('answered_at')->nullable();

            $table->tinyInteger('stars')->unsigned()->nullable(); // 1-5 stars rating
            $table->text('feedback')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
