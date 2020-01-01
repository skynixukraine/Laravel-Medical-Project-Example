<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\MessageResource;
use App\Models\Message;

class MessageController extends ApiController
{
    public function first(): MessageResource
    {
        return new MessageResource(Message::whereIsFirst(true)->firstOrFail());
    }

    public function show(Message $message)
    {
        return new MessageResource($message);
    }
}