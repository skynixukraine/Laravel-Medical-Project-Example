<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Enquire;

class EnquireCreated extends QueueableNotification
{
    private $enquire;

    protected $mailConfig = 'system';

    public function __construct(Enquire $enquire)
    {
        $this->enquire = $enquire;
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
    public function toMail($notifiable): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('New enquiry'))
            ->greeting(__('New enquiry'))
            ->line(__(':gender, :age just submitted a case.', [
                'gender' => __($this->enquire->gender === Enquire::GENDER_FEMALE ? 'Female' : 'Male'),
                'age' => $this->enquire->date_of_birth->diffInYears(),
            ]))
            ->line(__('Login to the doctors portal to answer it: :link', [
                'link' => config('app.url') . '/dashboard'
            ]));
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
