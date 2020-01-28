<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use App\Models\Enquire;
use App\Models\EnquireMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class DoctorCreatedEnquireMessage extends QueueableNotification
{
    protected $mailConfig = 'doctor';

    private $message;

    public function __construct(EnquireMessage $message)
    {
        $this->message = $message;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(Enquire $enquire): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('The dermatologist sent you a message'))
            ->greeting(__('Hi,'))
            ->line(new HtmlString($this->message->content));
    }
}
