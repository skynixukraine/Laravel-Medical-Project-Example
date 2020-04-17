<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultPricePolicyToDoctors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('pricing_policies')->truncate();

        $data = [
            'enquire_admins_fee' => '650',
            'enquire_total_price' => '1900',
            'enquire_display_price' => '19.00',
            'currency' => 'eur',
            'description' => 'Plan (19)'
        ];

        $id = \Illuminate\Support\Facades\DB::table('pricing_policies')->insertGetId($data);

        if (Schema::hasCOlumn('doctors', 'price_policy_id')) {

            Schema::table('doctors', function (Blueprint $table) {
                $table->dropForeign('doctors_price_policy_id_foreign');
                $table->dropColumn('price_policy_id');
            });
        }

        Schema::table('doctors', function (Blueprint $table) use ($id) {

            $table->unsignedInteger('price_policy_id')->nullable()->default($id);
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
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign('doctors_price_policy_id_foreign');
            $table->dropColumn('price_policy_id');
        });
    }
}
