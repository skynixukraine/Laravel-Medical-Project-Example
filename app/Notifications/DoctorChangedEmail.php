<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class DoctorChangedEmail extends QueueableNotification
{
    public function via(Doctor $doctor): array
    {
        return ['mail'];
    }

    public function toMail(Doctor $doctor): MailMessage
    {
        return $this->createMailMessage()
            ->subject(__('Ihre Kontoinformationen wurden geändert'))
            ->greeting(__('Hi,'))
            ->line(__('Sie haben kürzlich Ihre Kontoinformationen geändert. Unsere Aufzeichnungen zeigen, dass Sie die folgenden Informationen geändert haben:'))
            ->line(new HtmlString('<ul><li><b>' . __('E-mail') . '</b></li></ul>'))
            ->line(__("Falls Sie das nicht waren:"))
            ->line(new HtmlString(__('Jemand könnte Zugriff auf Ihr Konto haben. Bitte <u>ändern Sie jetzt Ihr Passwort</u>.')));
    }
}
