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
            ->subject(__('Online-Hautarzt.de Konto gelöscht'))
            ->greeting(__('Sehr geehrter :title :first_name :last_name,', [
                'title' => $this->doctor->title->name,
                'first_name' => $this->doctor->first_name,
                'last_name' => $this->doctor->last_name,
            ]))
            ->line(__('Ihr Online Hautarzt Konto wurde erfolgreich gelöscht.'));
            ->line(__('Vielen Dank, dass Sie Online Hautarzt ausprobiert haben.'));
            ->line(__('Wir würden uns freuen, falls Sie uns mitteilen, warum Sie Ihr Konto gelöscht haben. Schicken Sie einfach eine E-Mail an hilfe@online-hautarzt.de'));
      		->line(__('Vielen Dank'));
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
