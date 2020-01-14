<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\EnquireMessageCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnquireMessage extends Model
{
    public const SENDER_DOCTOR = 'DOCTOR';
    public const SENDER_PATIENT = 'PATIENT';

    protected $dispatchesEvents = [
        'created' => EnquireMessageCreated::class
    ];

    protected $fillable = ['content', 'sender', 'enquire_id'];

    public function enquire(): BelongsTo
    {
        return $this->belongsTo(Enquire::class);
    }
}
