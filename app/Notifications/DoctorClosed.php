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
            ->subject(__('Online-Hautarzt.de Account Closed'))
            ->greeting(__('Dear :title :first_name :last_name,', [
                'title' => $this->doctor->title->name,
                'first_name' => $this->doctor->first_name,
                'last_name' => $this->doctor->last_name,
            ]))
            ->line(__('Your online dermatologist account was successfully deleted.'));
            ->line(__('Thank you for trying online dermatologist.'));
            ->line(__('We would be happy if you let us know why you deleted your account. Just send an email to hilfe@online-hautarzt.de'));
      		->line(__('Many Thanks'));
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
