<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EnquireVerifiedEmail;

class SendEnquireVerifiedEmailNotification
{
    public function handle(EnquireVerifiedEmail $event): void
    {
        $enquire = $event->getEnquire();
        $enquire->notify(new \App\Notifications\EnquireVerifiedEmail($enquire));
    }
}