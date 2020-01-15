<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Services\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

abstract class QueueableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mailConfig = 'system';

    public function __wakeup()
    {
        parent::__wakeup();
        MailService::useMailConfig($this->mailConfig);
    }
}