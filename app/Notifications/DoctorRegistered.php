<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
        $this->doctor->sendEmailVerificationNotification();
    }
}
