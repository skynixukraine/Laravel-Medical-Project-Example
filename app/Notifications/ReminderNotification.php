<?php

namespace App\Notifications;

use App\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReminderNotification extends MyNotification
{
    use Queueable;

    private $submission;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($submission)
    {
        $this->submission = $submission;
        parent::__construct();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
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

        return (new MailMessage)->markdown('mail.reminder', [
            'submission' => $this->submission
        ])
            ->subject('Noch etwa '. floor($this->submission->responsetime/2) . ' Stunden für die Bearbeitung')
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
