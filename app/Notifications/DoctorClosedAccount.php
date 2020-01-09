<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorClosedAccount extends Notification
{
    use Queueable;

    /**
     * @var Doctor
     */
    private $doctor;

    public function __construct(Doctor $doctor)
    {
        $this->doctor = $doctor;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('mail.doctor.doctor-closed-account', ['doctor' => $this->doctor])
            ->subject('Account closed');
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
