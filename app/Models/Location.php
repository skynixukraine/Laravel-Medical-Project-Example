<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'lat',
        'lng',
        'type',
    ];

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }
}