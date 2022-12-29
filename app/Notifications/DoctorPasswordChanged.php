<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Support\HtmlString;

class DoctorPasswordChanged extends QueueableNotification
{
    public function via(Doctor $doctor)
    {
        return ['mail'];
    }

    public function toMail(Doctor $doctor)
    {
        return $this->createMailMessage()
            ->subject(__('Your account information has been changed'))
            ->greeting(__('Dear :title :first_name :last_name,', [
                'title' => $doctor->title->name,
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
            ]))
            ->line(__('You recently made changes to your Medical Example account. Our records indicate that you changed the following information:'))
            ->line(new HtmlString('<ul><li><b>' . __('Password') . '</b></li></ul>'))
            ->line(__("If this wasn't you:"))
            ->line(__('Please contact support immediately.'));
    }
}