<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class DoctorChangedEmail extends QueueableNotification
{
    public function via(Doctor $doctor): array
    {
        return ['mail'];
    }

    public function toMail(Doctor $doctor): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('Your account information has been changed'))
            ->greeting(__('Hi,'))
            ->line(__('You recently made changes to your Online Hautarzt account. Our records indicate that you changed the following information:'))
            ->line(new HtmlString('<ul><li><b>' . __('E-mail') . '</b></li></ul>'))
            ->line(__("If this wasn't you:"))
            ->line(new HtmlString(__('Your account may have been compromised. Please <u>change your password now.</u>')));
    }
}
