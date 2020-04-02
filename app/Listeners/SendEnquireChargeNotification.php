<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Notifications\EnquireCharge;

class SendEnquireChargeNotification
{
    public function handle(\App\Events\EnquireCharge $event): void
    {
        $enquire = $event->getEnquire();
        $enquire->notify(new EnquireCharge($enquire));

    }
}