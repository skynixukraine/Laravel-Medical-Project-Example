<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Traits\DoctorInfo;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorRequestedActivation extends QueueableNotification
{
    use DoctorInfo;

    protected $mailConfig = 'admin';

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
        return $this->createMailMessage()
            ->subject(__('Your application is under review'))
            ->greeting(__('Dear Colleague,'))
            ->line(__('Thank you, your application is now being reviewed. We will notify you as soon as your account has been activated and is available on the website.'));
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
