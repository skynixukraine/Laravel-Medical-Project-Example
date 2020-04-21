<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminFeeColumnToBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->integer('admin_fee');
            $table->integer('invoice_1A_factor');
            $table->integer('invoice_1A_price');
            $table->integer('invoice_5A_factor');
            $table->integer('invoice_5A_price');
            $table->integer('invoice_75A_factor');
            $table->integer('invoice_75A_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('invoice_admin_fee');
            $table->dropColumn('invoice_1A_factor');
            $table->dropColumn('invoice_1A_price');
            $table->dropColumn('invoice_5A_factor');
            $table->dropColumn('invoice_5A_price');
            $table->dropColumn('invoice_75A_factor');
            $table->dropColumn('invoice_75A_price');
        });
    }
}
