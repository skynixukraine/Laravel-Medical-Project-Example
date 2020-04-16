<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class AdminDoctorsVerifyEmail extends QueueableNotification
{

    private $doctor;
    
    public function __construct(Doctor $doctor)
    {
        $this->doctor = $doctor;
    }
    
    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('New doctor'))
            ->greeting(__('Hello,'))
            ->line(__('New doctor'))
            ->line($this->doctor->email);
    }
    
}