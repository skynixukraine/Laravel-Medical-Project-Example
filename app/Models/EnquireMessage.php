<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquireMessage extends Model
{
    public const SENDER_DOCTOR = 'DOCTOR';
    public const SENDER_PATIENT = 'PATIENT';

    protected $fillable = ['content', 'enquire_message_id', 'sender'];

    protected $casts = [
        'enquire_message_id' => 'int'
    ];
}
