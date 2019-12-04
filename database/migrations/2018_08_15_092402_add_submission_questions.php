<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubmissionQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->text('other_symptoms')->nullable();
            $table->text('since_other')->nullable()->after('since');
            $table->enum('side', ['einseitig', 'beidseitig', 'nicht sicher']);
            $table->text('affected_area'); // why would this be nullable. User should always be able to answer this.
            $table->boolean('treated');
            $table->text('treatment')->nullable();
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
            $table->dropColumn('other_symptoms');
            $table->dropColumn('since_other');
            $table->dropColumn('side');
            $table->dropColumn('affected_area');
            $table->dropColumn('treated');
            $table->dropColumn('treatment');
        });
    }
}
