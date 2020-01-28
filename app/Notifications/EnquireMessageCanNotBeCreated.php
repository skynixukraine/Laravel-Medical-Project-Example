<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Enquire;

class EnquireMessageCanNotBeCreated extends QueueableNotification
{
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(Enquire $enquire): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('Your case was closed'))
            ->greeting(__('Hi,'))
            ->line(__('You attempt to send a message according to your case :num but it was already closed.', ['num' => $enquire->id]));
    }
}
