<?php

use App\Models\Enquire;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorStatusInEnquiresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Enquire::query()->update(['status' => Enquire::STATUS_ARCHIVED]);

        DB::statement('ALTER TABLE enquires MODIFY COLUMN status ENUM(\''
            . Enquire::STATUS_NEW . '\', \'' . Enquire::STATUS_WAIT_DOCTOR_RESPONSE . '\', \''
            . Enquire::STATUS_WAIT_PATIENT_RESPONSE . '\', \'' . Enquire::STATUS_RESOLVED . '\', \''
            . Enquire::STATUS_ARCHIVED . '\') DEFAULT \'' . Enquire::STATUS_NEW . '\';');

        Enquire::query()->update(['status' => Enquire::STATUS_NEW]);

        Schema::table('enquires', function (Blueprint $table) {
            $table->boolean('is_seen')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Enquire::query()->update(['status' => Enquire::STATUS_ARCHIVED]);

        DB::statement('ALTER TABLE enquires MODIFY COLUMN status ENUM(\''
            . 'UNREAD' . '\', \'' . 'READ' . '\', \''
            . Enquire::STATUS_ARCHIVED . '\') DEFAULT \'' . 'UNREAD' . '\';');

        Enquire::query()->update(['status' => 'UNREAD']);

        Schema::table('enquires', function (Blueprint $table) {
            $table->dropColumn('is_seen');
        });
    }
}
