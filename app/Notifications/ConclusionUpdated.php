<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Enquire;

class ConclusionUpdated extends QueueableNotification
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

    public function toMail(): MailMessage
    {
        $mail = $this->createMailMessage()
            ->subject(__('Your Online Hautarzt case was answered'))
            ->greeting(__('Dear patient,'))
            ->line(__('Your case has been processed and the assessment by the dermatologist of your choice has been completed. Please open this link: :link to access the dermatologist\'s answer.', [
                'link' => $this->conclusionUrl(),
            ]))
            ->line(__('Get well!'));

        if ($this->enquire->billing) {
            $mail->attachData($this->enquire->invoicePdf(), $this->enquire->invoiceNamePDF(), [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }

    /**
     * @return string
     */
    protected function conclusionUrl(): string
    {
        return config('app.url') . '/conclusions/' . base64_encode($this->enquire->hash);
    }
}
