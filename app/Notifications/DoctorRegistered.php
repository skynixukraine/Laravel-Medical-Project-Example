<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorRegistered extends Notification
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

    public function via()
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('mail.registration-completed', ['doctor' => $this->doctor,])
            ->subject('Ihre Registrierung');
    }
}
