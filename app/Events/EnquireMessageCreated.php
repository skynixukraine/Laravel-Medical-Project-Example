<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\EnquireMessage;

class EnquireMessageCreated
{
    /**
     * @var EnquireMessage
     */
    private $message;

    /**
     * EnquireMessageCreated constructor.
     * @param EnquireMessage $message
     */
    public function __construct(EnquireMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @return EnquireMessage
     */
    public function getMessage(): EnquireMessage
    {
        return $this->message;
    }
}