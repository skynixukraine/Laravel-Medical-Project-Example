<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Enquire extends Model
{
    public const GENDER_MALE = 'male';
    public const GENDER_FEMALE = 'female';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone_number',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'model');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EnquireAnswer::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}