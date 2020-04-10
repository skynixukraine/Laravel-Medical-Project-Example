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
        //config('mail.extra.contact.to')
        Notification::route('mail', 'alexey.vnu+support@gmail.com')
            ->notify(new ContactAdminNotification($contact));
    }
}