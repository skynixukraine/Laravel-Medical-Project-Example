<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Traits\DoctorInfo;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class DoctorActivated extends QueueableNotification
{
    use DoctorInfo;

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
        return $this->createMailMessage()
            ->subject(__('Account successfully verified'))
            ->line(__('Dear colleague,'))
            ->line(__('We appreciate your interest!'))
            ->line(__('Your registration has been successfully verified. Now you can log in at online-hautarzt.de/login.'))
            ->line(new HtmlString('Please complete the information in your <a href=\"/account/personal-information\"> Konto </a>, in particular the billing details :billing and request approval from the administrator. You will enter the bank details with our payment provider Stripe, who will handle the automatic payment - so you will receive your remuneration automatically transferred to the bank account almost in real time. We cannot therefore activate this data before it is stored.'))
            ->line(__('Please fill in the details in your profile and at the end click on “Apply for activation” at the bottom of the page as soon as you are finished.'))
            ->line(__('If you have any questions, simply send us an email to hilfe@online-hautarzt.de'))
            ->line(__('Kind regards and thank you'));
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
