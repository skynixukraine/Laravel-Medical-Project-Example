<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Traits\DoctorInfo;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorDeleted extends QueueableNotification
{
    /**
     * Doctors will always be notified by Email
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * @param array $doctor
     * @param $notifiable
     * @return mixed
     */
    public function toMail(array $doctor, $notifiable)
    {
        return $this->createMailMessage()
            ->subject(__('Online-Hautarzt.org Account Closed'))
            ->greeting(__('Dear :title :first_name :last_name,', $doctor))
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
