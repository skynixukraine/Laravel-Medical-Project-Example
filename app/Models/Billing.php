<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billing extends Model
{
    protected $fillable = [
        'amount', 'currency', 'enquire_id'
    ];

    public function enquire(): BelongsTo
    {
        return $this->belongsTo(Enquire::class);
    }
}
