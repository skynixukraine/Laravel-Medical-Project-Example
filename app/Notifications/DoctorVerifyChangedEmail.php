<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorVerifyChangedEmail extends QueueableNotification
{
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
     * @param  mixed  $doctor
     * @return MailMessage
     */
    public function toMail($doctor): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('Email address change request'))
            ->greeting(__('Dear :title :first_name :last_name,', [
                'title' => $doctor->title,
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
            ]))
            ->line(__('You have made a request to change your e-mail address. To complete this process, please click here:'))
            ->action(__('Verify Email Address'), $this->verificationUrl($doctor));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable): string
    {
        $query = parse_url(URL::temporarySignedRoute(
            'doctors.verify-email', Carbon::now()->addHours(3), [
            'id' => $notifiable->getKey()
        ]))['query'] ?? '';

        return config('app.url') . '/verify?' . $query;
    }
}
