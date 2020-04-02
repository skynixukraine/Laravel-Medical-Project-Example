<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ConclusionUpdated;
use App\Notifications\ConclusionUpdated as ConclusionUpdatedNotification;

class SendConclusionUpdatedNotification
{
    public function handle(ConclusionUpdated $event): void
    {
        $enquire = $event->getEnquire();
        $enquire->notify(new ConclusionUpdatedNotification($enquire));
    }
}