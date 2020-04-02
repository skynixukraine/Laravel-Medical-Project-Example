<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EnquireUpdated;
use App\Notifications\EnquireClosed as EnquireClosedNotification;

class SendEnquireClosedNotification
{
    public function handle(EnquireUpdated $event): void
    {
        $enquire = $event->getEnquire();

        if ($enquire->getOriginal('conclusion') === null && $enquire->conclusion !== null) {
            $enquire->notify(new EnquireClosedNotification());
        }
    }
}