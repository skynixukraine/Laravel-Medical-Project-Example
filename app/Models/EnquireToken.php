<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnquireToken extends Model
{
    protected $fillable = ['access_token', 'expires_at'];

    protected $casts = ['expires_at' => 'datetime'];

    public function enquire(): BelongsTo
    {
        return $this->belongsTo(Enquire::class);
    }
}