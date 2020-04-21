<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricePolicyIdToDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->unsignedInteger('price_policy_id')->nullable();
            $table->foreign('price_policy_id')
                ->references('id')
                ->on('pricing_policies')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('doctors', 'price_policy_id')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->dropForeign('doctors_price_policy_id_foreign');
                $table->dropColumn('price_policy_id');
            });
        }
    }
}
