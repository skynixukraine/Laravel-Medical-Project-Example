<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->unique();
            $table->string('title');
            $table->string('country');
        });

        \Illuminate\Support\Facades\DB::table('payment_methods')->insert([
            [
                'name' => 'giropay',
                'title' => 'Giropay',
                'country' => 'DE'
            ],
            [
                'name' => 'sofort',
                'title' => 'SOFORT',
                'country' => 'DE'
            ],
            [
                'name' => 'credit_card',
                'title' => 'Credit Card',
                'country' => 'DE'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
