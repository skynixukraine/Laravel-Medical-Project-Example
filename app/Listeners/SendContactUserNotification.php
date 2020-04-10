<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactCreated;
use App\Notifications\ContactUserNotification;

class SendContactUserNotification
{
    public function handle(ContactCreated $event): void
    {
        $contact = $event->getContact();
        $contact->notify(new ContactUserNotification($contact));
    }
}