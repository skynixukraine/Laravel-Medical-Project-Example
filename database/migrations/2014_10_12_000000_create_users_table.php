<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('photo')->nullable();
            $table->enum('gender', ['m', 'f']);
            $table->string('title')->nullable()->default('');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthday');
            $table->string('birthplace');
            $table->string('street');
            $table->string('zip');
            $table->string('city');
            $table->string('country');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->smallInteger('graduation_year')->nullable();
            $table->text('reason_for_application');
            $table->string('user_id');
            $table->string('password');
            $table->enum('status', ['registered', 'confirmed', 'blocked'])->default('registered');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
