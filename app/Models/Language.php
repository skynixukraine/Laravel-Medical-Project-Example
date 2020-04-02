<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Language extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name'];

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class);
    }
}