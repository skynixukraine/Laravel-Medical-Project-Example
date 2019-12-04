<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripeToSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->decimal('amount', 6, 2)->nullable()->after('responsetime');
            $table->string('stripe_source_id')->nullable();
            $table->string('stripe_source_object')->nullable();
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
            $table->dropColumn('amount');
            $table->dropColumn('stripe_source_id');
            $table->dropColumn('stripe_source_object');
        });
    }
}
