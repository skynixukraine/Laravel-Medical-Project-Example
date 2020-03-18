<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Notifications\Messages\MailMessage;

use function GuzzleHttp\Psr7\build_query;

class DoctorVerifyChangedEmail extends QueueableNotification
{
    private $token;

    private $email;

    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via(Doctor $doctor): array
    {
        return ['mail'];
    }

    public function toMail(Doctor $doctor): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('Email address change request'))
            ->replyTo($this->email)
            ->greeting(__('Dear :title :first_name :last_name,', [
                'title' => $doctor->title->name,
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
            ]))
            ->line(__('You have made a request to change your e-mail address. To complete this process, please click here:'))
            ->action(__('Verify Email Address'), $this->verificationUrl($doctor));
    }

    protected function verificationUrl(Doctor $doctor): string
    {
        return config('app.url') . '/change-email-verify?' . build_query([
            'id' => $doctor->id,
            'token' => $this->token,
        ]);
    }
}
