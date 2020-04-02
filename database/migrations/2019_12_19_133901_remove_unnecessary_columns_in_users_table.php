<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnnecessaryColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
                $table->dropColumn([
                    'photo', 'gender', 'title', 'birthday', 'birthplace',
                    'street', 'zip', 'city', 'country', 'lat', 'lng',
                    'graduation_year', 'reason_for_application', 'user_id', 'status'
                ]);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
                $table->string('photo')->nullable();
                $table->string('title')->nullable()->default('');
                $table->enum('gender', ['m', 'f']);
                $table->string('birthplace');
                $table->date('birthday')->nullable();
                $table->string('zip');
                $table->string('street');
                $table->string('country');
                $table->string('city');
                $table->decimal('lat', 10, 8)->nullable();
                $table->decimal('lng', 11, 8)->nullable();
                $table->smallInteger('graduation_year')->nullable();
                $table->text('reason_for_application');
                $table->string('user_id');
                $table->enum('status', ['registered', 'confirmed', 'blocked'])->default('registered');
            }
        );
    }
}
