<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Facades\Storage;

class EnquireAnswer extends Model
{
    public $timestamps = false;

    protected $fillable = ['message_id', 'enquire_id', 'message_option_id', 'value'];

    public function enquire(): BelongsTo
    {
        return $this->BelongsTo(Enquire::class);
    }

    public function message(): BelongsTo
    {
        return $this->BelongsTo(Message::class);
    }

    public function message_option(): BelongsTo
    {
        return $this->BelongsTo(MessageOption::class);
    }

    public function option(): BelongsTo
    {
        return $this->BelongsTo(MessageOption::class, 'message_option_id', 'id');
    }

    public function prepareValue()
    {
        if ($this->message->type == \App\Models\Message::TYPE_IMAGE) {
            return Storage::getEnquireImageBase64($this->value);
        }

        if ($this->message->type == \App\Models\Message::TYPE_SELECT) {
            return implode(', ', json_decode($this->value));
        }

        return $this->value;
    }
}