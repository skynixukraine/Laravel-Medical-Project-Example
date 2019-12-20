<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'address',
        'latitude',
        'longitude',
        'city',
        'state',
        'postal_code',
        'country',
        'doctor_id'
    ];

    public function doctor(): BelongsTo
    {
        return $this->BelongsTo(Doctor::class);
    }
}