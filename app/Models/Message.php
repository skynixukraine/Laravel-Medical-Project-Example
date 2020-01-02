<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    public $timestamps = false;

    public const TYPE_SIMPLE = 'simple';
    public const TYPE_TEXT = 'text';
    public const TYPE_RADIO = 'radio';
    public const TYPE_SELECT = 'select';
    public const TYPE_BODY_SELECT = 'body-select';
    public const TYPE_IMAGE = 'image';

    protected $fillable = [
        'title',
        'content',
        'questioner',
        'type',
        'button',
        'is_first',
        'next_message_id',
    ];

    public function next(): BelongsTo
    {
        return $this->BelongsTo(__CLASS__, 'next_message_id', 'id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(MessageOption::class)
            ->leftJoin('messages', 'message_options.message_id', '=', 'messages.id')
            ->whereIn('messages.type', [self::TYPE_SELECT, self::TYPE_RADIO, self::TYPE_BODY_SELECT]);
    }
}