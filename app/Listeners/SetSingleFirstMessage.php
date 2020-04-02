<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MessageSaved;
use App\Models\Message;

class SetSingleFirstMessage
{
    /**
     * Handle the event.
     *
     * @param  MessageSaved  $event
     * @return void
     */
    public function handle(MessageSaved $event)
    {
        $message = $event->getMessage();

        if ($message->is_first) {
            Message::query()
                ->where('id', '!=', $message->id)
                ->where('is_first', true)
                ->update(['is_first' => false]);
        }
    }
}
