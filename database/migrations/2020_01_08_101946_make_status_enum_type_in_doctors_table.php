<?php

use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class MakeStatusEnumTypeInDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE doctors MODIFY COLUMN status ENUM(\''
                      . Doctor::STATUS_CREATED . '\', \'' . Doctor::STATUS_ACTIVATED . '\', \''
                      . Doctor::STATUS_ACTIVATION_REQUESTED . '\', \'' . Doctor::STATUS_CLOSED . '\', \''
                      . Doctor::STATUS_DEACTIVATED . '\') DEFAULT \'' . Doctor::STATUS_CREATED . '\';');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE doctors MODIFY COLUMN status VARCHAR(255) DEFAULT \'' . Doctor::STATUS_CREATED . '\';');
    }
}
