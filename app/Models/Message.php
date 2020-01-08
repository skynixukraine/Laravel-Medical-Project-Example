<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\MessageSaved;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    public $timestamps = false;

    public const TYPE_SIMPLE = 'SIMPLE';
    public const TYPE_TEXT = 'TEXT';
    public const TYPE_RADIO = 'RADIO';
    public const TYPE_SELECT = 'SELECT';
    public const TYPE_BODY_SELECT = 'BODY-SELECT';
    public const TYPE_IMAGE = 'IMAGE';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => MessageSaved::class,
    ];

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
            ->select('message_options.*')
            ->leftJoin('messages', 'message_options.message_id', '=', 'messages.id')
            ->whereIn('messages.type', [self::TYPE_SELECT, self::TYPE_RADIO]);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EnquireAnswer::class);
    }
}