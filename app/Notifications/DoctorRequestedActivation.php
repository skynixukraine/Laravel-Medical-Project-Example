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
            ->line(__('Thank you for your application for our doctors portal.'))
            ->line(__('We can only approve your application and fully activate your account once we have received '
                . 'a) your medical degree and b) your board-certification for dermatology and c) a profile picture.'))
            ->line(__('Please make sure that you have uploaded this documents under your account. If not, please do so.'));
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
