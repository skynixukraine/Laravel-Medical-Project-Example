<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Doctor;
use App\Models\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
