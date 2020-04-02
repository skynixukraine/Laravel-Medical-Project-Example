<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Enquire;

class EnquireCreated extends QueueableNotification
{
    private $enquire;

    public function __construct(Enquire $enquire)
    {
        $this->enquire = $enquire;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(Doctor $doctor): MailMessage
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
}
