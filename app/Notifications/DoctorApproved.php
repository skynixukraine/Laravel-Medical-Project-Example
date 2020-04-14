<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Traits\DoctorInfo;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorApproved extends QueueableNotification
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
            ->subject(__('Your Doctor Profile is approved and is now visible on Online-Hautarzt.de site'))
            ->greeting(__('Hello,'))
            ->line(__('Your profile has been released and you can view it at online-hautarzt.de/hautarzt. Congratulations & welcome on board!'))
            ->line(__('What\'s next now? You should inform your patients that you can also be reached online for their medical problems if they cannot or do not want to come in - for example, because you are on vacation or your patients are not in town or because of a pandemic is in progress, or or or. How do you best do that? You can find out about this in the doctor portal under  : “More Patients” (bottom left), log in here and get started: https://online-hautarzt.de/login'))
            ->line(__('Have fun with the online treatment and good luck!'))
            ->line(__('Your team of online dermatologist on site'))
            ->line(__('If you have any questions, please write to: hilfe@online-hautarzt.de'));
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
