<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Traits\DoctorInfo;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorClosed extends QueueableNotification
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
        return $this->createMailMessage()
            ->subject(__('Online-Hautarzt.org Account Closed'))
            ->greeting(__('Dear :title :first_name :last_name,', [
                'title' => $this->doctor->title->name,
                'first_name' => $this->doctor->first_name,
                'last_name' => $this->doctor->last_name,
            ]))
            ->line(__('Your Online Hautarzt Account was terminated successfully. Thank you for giving Online Hautarzt a try.'));
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
