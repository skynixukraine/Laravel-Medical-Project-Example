<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EnquireCreated;
use App\Notifications\EnquireVerifyEmail;

class SendEnquireVerifyEmailNotification
{
    public function handle(EnquireCreated $event): void
    {
        $enquire = $event->getEnquire();
        $enquire->notify(new EnquireVerifyEmail());
    }
}