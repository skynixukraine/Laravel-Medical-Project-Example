<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Notifications\Messages\MailMessage;

class ContactUserNotification extends QueueableNotification
{
    private $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('New Support Request was received'))
            ->greeting(__('Hi,'))
            ->line(__('We’ve received your support request and will get in touch with you shortly'))
            ->line(__('Thank you'));
    }
}
