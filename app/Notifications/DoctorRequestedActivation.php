<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use App\Traits\DoctorInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorRequestedActivation extends Notification
{
    use Queueable, DoctorInfo;

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
        return (new MailMessage)
            ->markdown('mail.doctor.doctor-activation-request-created', ['doctor' => $this->doctor])
            ->subject('Activation request created');
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
