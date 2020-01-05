<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function option(): BelongsTo
    {
        return $this->BelongsTo(MessageOption::class, 'message_option_id', 'id');
    }
}