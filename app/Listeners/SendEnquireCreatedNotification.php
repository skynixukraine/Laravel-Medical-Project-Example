<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EnquireCreated;
use App\Notifications\EnquireCreated as EnquireCreatedNotification;

class SendEnquireCreatedNotification
{
    public function handle(EnquireCreated $event): void
    {
        $enquire = $event->getEnquire();
        $enquire->doctor->notify(new EnquireCreatedNotification($enquire));
    }
}