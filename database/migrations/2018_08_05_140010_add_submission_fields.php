<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubmissionFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->boolean('diagnosis_possible')->nullable();
            $table->text('diagnosis')->nullable();
            $table->boolean('requires_doctors_visit')->nullable();
            $table->boolean('did_recommend_medicine')->nullable();
            $table->text('recommended_medicine')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('diagnosis_possible');
            $table->dropColumn('diagnosis');
            $table->dropColumn('requires_doctors_visit');
            $table->dropColumn('did_recommend_medicine');
            $table->dropColumn('recommended_medicine');
        });
    }
}
