<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Enquire;
use Illuminate\Notifications\Messages\MailMessage;

class PatientCreatedEnquireMessage extends QueueableNotification
{
    public function via()
    {
        return ['mail'];
    }

    public function toMail(Enquire $enquire): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('The patient sent you a message'))
            ->greeting(__('Hi,'))
            ->line(__('A new message regarding case no :num is accessible in the doctors portal. Click here to access it:', [
                'num' => $enquire->id
            ]))
            ->action(__('View case'), config('app.url') . '/cases');
    }
}
