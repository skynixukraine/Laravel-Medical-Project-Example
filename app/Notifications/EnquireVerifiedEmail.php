<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Traits\DoctorInfo;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class EnquireVerifiedEmail extends QueueableNotification
{
    use DoctorInfo;

    /**
     * Doctors will always be notified by Email
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(Lang::getFromJson('E-mail verified'))
            ->line(Lang::getFromJson('Your e-mail was successfully verified.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
