<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EnquireMessageCreated;
use App\Models\EnquireMessage;
use App\Notifications\EnquireMessageCreated as EnquireMessageCreatedNotification;

class SendEnquireMessageCreatedNotification
{
    public function handle(EnquireMessageCreated $event): void
    {
        $message = $event->getMessage();
        $message->sender === EnquireMessage::SENDER_DOCTOR
            ? $message->enquire->notify(new EnquireMessageCreatedNotification($message))
            : $message->enquire->doctor->notify(new EnquireMessageCreatedNotification($message));
    }
}
