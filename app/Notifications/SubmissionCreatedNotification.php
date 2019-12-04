<?php

namespace App\Notifications;

use App\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SubmissionCreatedNotification extends MyNotification
{
    use Queueable;

    private $submission,
        $partner;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($submission)
    {
        $this->submission = $submission;
        $this->partner = Partner::find($submission->partner_id);
        parent::__construct($this->partner);
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
        return (new MailMessage)->markdown('mail.submission-created-' . $this->partner->partner_id, [
            'submission' => $this->submission
        ])
            ->subject(__("case-submit.case_submitted"))
            ->from($this->partner->mail_from_address, $this->partner->mail_from_name)
            ->replyTo($this->partner->mail_from_address);
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
