<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number')->after('email')->unique()->nullable(false);
            $table->string('email')->unique()->nullable(false)->change();
            $table->string('medical_degree')->after('photo')->nullable()->unique();
            $table->string('board_certification')->after('medical_degree')->nullable()->unique();
            $table->string('photo')->unique()->nullable()->change();
            $table->dropColumn('prefix');
            $table->dropColumn('slug');
            $table->string('title')->after('board_certification')->nullable(true);
            $table->string('first_name')->nullable(true)->change();
            $table->string('last_name')->nullable(true)->change();
            $table->string('description')->nullable(true)->change();
            $table->unsignedInteger('region_id')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
            $table->dropColumn('phone_number');
            $table->dropColumn('medical_degree');
            $table->dropColumn('board_certification');
            $table->string('photo')->change();
            $table->string('email')->nullable(false)->change();
            $table->string('slug');
            $table->string('prefix');
            $table->dropColumn('title');
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
            $table->string('description')->nullable(false)->change();
            $table->unsignedInteger('region_id')->nullable(false)->change();
        });
    }
}
