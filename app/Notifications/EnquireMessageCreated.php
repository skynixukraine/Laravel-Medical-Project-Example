<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\EnquireMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class EnquireMessageCreated extends QueueableNotification
{
    /**
     * @var EnquireMessage
     */
    private $message;

    protected $mailConfig = 'doctor';

    /**
     * DoctorCreatedEnquireMessage constructor.
     * @param EnquireMessage $message
     */
    public function __construct(EnquireMessage $message)
    {
        $this->message = $message;
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
        return $this->{'makeMailFrom' . Str::studly($this->message->sender)}();
    }

    private function makeMailFromDoctor()
    {
        $doctor = $this->message->enquire->doctor;
        return (new MailMessage())
            ->subject(Lang::getFromJson('Your doctor ' . $doctor->title . ' ' . $doctor->first_name . ' ' . $doctor->last_name . ' sent you a message'))
            ->line(new HtmlString($this->message->content));
    }

    private function makeMailFromPatient()
    {
        $enquire = $this->message->enquire;

        return (new MailMessage())
            ->subject(Lang::getFromJson('Your patient ' . $enquire->first_name . ' ' . $enquire->last_name . ' sent you a message'))
            ->line(new HtmlString($this->message->content));
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
