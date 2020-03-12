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

    public function toMail(Doctor $doctor): MailMessage
    {
        die(var_dump($this->conclusionUrl()));
        return $this->createMailMessage()
            ->subject(__('New conclusion'))
            ->greeting(__('New conclusion'))
            ->line(__('The conclusion is ready and will be available within 6 weeks.'))
            ->action(__('Conclusion'), $this->conclusionUrl());
    }

    /**
     * @return string
     */
    protected function conclusionUrl(): string
    {
        return config('app.url') . '/conclusions';
    }
}
