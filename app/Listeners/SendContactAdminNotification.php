<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactCreated;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactAdminNotification;

class SendContactAdminNotification
{
    public function handle(ContactCreated $event): void
    {
        $contact = $event->getContact();
        Notification::route('mail', config('mail.extra.contact.to'))
            ->notify(new ContactAdminNotification($contact));
    }
}