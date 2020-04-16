<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricePolicy extends Model
{

    protected $table = 'pricing_policies';

    protected $fillable = [
        'enquire_admins_fee',
        'enquire_total_price',
        'enquire_display_price',
        'description',
        'currency',
    ];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }
}
