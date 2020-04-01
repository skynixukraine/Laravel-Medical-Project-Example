<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorVerifyEmail extends QueueableNotification
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
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('Bitte bestätigen Sie Ihre E-Mail Adresse'))
            ->greeting(__('Hallo,'))
            ->line(__('Bitte bestätigen Sie Ihre E-Mail Adresse durch einen Klick auf den folgenden Link:'))
            ->action(__('Bestätigungs-E-Mail'), $this->verificationUrl($notifiable));
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
