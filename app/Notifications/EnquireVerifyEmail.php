<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;

class EnquireVerifyEmail extends QueueableNotification
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
            ->subject(__('Please Confirm Your E-Mail Address'))
            ->greeting(__('Hello,'))
            ->line(__('Please confirm your email address by clicking on the following link:'))
            ->action(__('Verify Email Address'), $this->verificationUrl($notifiable));
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
            'enquire.verify-email', Carbon::now()->addHours(3), [
            'id' => $notifiable->getKey()
        ]))['query'] ?? '';

        return config('app.url') . '/verify?' . $query;
    }
}
