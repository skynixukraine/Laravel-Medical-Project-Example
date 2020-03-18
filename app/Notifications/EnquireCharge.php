<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Enquire;

class EnquireCharge extends QueueableNotification
{
    private $enquire;

    public function __construct(Enquire $enquire)
    {
        $this->enquire = $enquire;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(Enquire $enquire): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('Enquiry paid'))
            ->greeting(__('Enquiry paid'))
            ->line(__('Enquire has been paid'));
    }
}
