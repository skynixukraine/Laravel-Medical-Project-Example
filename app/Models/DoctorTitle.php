<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DoctorTitle extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }
}
