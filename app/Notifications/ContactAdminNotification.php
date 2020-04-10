<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Notifications\Messages\MailMessage;

class ContactAdminNotification extends QueueableNotification
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
            ->subject(__('New Support Request'))
            ->line(__('You’ve received a new support request'))
            ->line($this->contact->email)
            ->line($this->contact->name)
            ->line($this->contact->body);
    }
}
