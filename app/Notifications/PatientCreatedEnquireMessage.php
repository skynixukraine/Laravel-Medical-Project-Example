<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use App\Models\EnquireMessage;
use Illuminate\Notifications\Messages\MailMessage;

class PatientCreatedEnquireMessage extends QueueableNotification
{
    private $message;

    public function __construct(EnquireMessage $message)
    {
        $this->message = $message;
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail(Doctor $doctor): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('The patient sent you a message'))
            ->greeting(__('Hi,'))
            ->line(__('A new message regarding case no :num is accessible in the doctors portal. Click here to access it:', [
                'num' => $this->message->enquire->id
            ]))
            ->action(__('View the case'), config('app.url') . '/cases');
    }
}
