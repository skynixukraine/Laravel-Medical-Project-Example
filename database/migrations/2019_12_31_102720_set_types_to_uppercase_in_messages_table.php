<?php

use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetTypesToUppercaseInMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE messages MODIFY COLUMN type ENUM(\''
                      . Message::TYPE_SIMPLE . '\', \'' . Message::TYPE_TEXT . '\', \''
                      . Message::TYPE_RADIO . '\', \'' . Message::TYPE_SELECT . '\', \''
                      . Message::TYPE_BODY_SELECT . '\', \'' . Message::TYPE_IMAGE . '\')');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE messages MODIFY COLUMN type ENUM(' .
            "'simple', 'text', 'radio', 'select', 'body-select', 'image')");
    }
}
