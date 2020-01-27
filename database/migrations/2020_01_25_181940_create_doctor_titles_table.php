<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_titles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
        });

        DB::statement('UPDATE doctors SET title = null WHERE title is not null;');
        DB::statement('ALTER TABLE doctors RENAME COLUMN title TO title_id;');
        DB::statement('ALTER TABLE doctors MODIFY COLUMN title_id integer unsigned;');

        Schema::table('doctors', function (Blueprint $table) {
            $table->foreign('title_id')
                ->references('id')
                ->on('doctor_titles')
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
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign('doctors_title_id_foreign');
        });

        DB::statement('ALTER TABLE doctors RENAME COLUMN title_id TO title;');
        DB::statement('ALTER TABLE doctors MODIFY COLUMN title varchar(255);');

        Schema::dropIfExists('doctor_titles');
    }
}
