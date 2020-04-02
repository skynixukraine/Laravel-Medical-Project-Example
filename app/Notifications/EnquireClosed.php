<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Enquire;

class EnquireClosed extends QueueableNotification
{
    protected $mailConfig = 'doctor';

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(Enquire $enquire): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('Your case was answered'))
            ->greeting(__('Hi,'))
            ->line(__('Your case :num was answered', ['num' => $enquire->id]))
            ->line(__('You can download it using link below:'))
            ->action(__('Download answer'), config('app.url') . '/' . $enquire->id . '/download');
    }
}
