<?php

namespace App\Notifications;

use App\Partner;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationCompletedNotification extends MyNotification
{
    use Queueable;

    private $user;

    /**
     * RegistrationCompletedNotification constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        parent::__construct();
    }

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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $partner = Partner::find(1);

        return (new MailMessage)->markdown('mail.registration-completed', [
            'user' => $this->user
        ])
            ->subject('Ihre Registrierung')
            ->from($partner->mail_from_address, $partner->mail_from_name)
            ->replyTo($partner->mail_from_address);
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
