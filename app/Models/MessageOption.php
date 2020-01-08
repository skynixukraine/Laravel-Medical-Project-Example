<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageOption extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'message_id',
        'value',
        'next_message_id'
    ];

    public function next(): BelongsTo
    {
        return $this->BelongsTo(Message::class, 'next_message_id', 'id');
    }

    public function message(): BelongsTo
    {
        return $this->BelongsTo(Message::class, 'message_id', 'id');
    }
}