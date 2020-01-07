<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MessageSaved;
use App\Events\UserRegisteredEvent;
use App\Models\Message;
use App\Notifications\RegistrationCompletedNotification;
use Illuminate\Support\Facades\Notification;

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
