<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Enquire extends Model
{
    public const STATUS_UNREAD = 'UNREAD';
    public const STATUS_READ = 'READ';
    public const STATUS_ARCHIVED = 'ARCHIVED';

    public const GENDER_MALE = 'MALE';
    public const GENDER_FEMALE = 'FEMALE';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone_number',
        'email',
        'doctor_id',
        'status',
        'conclusion'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_paid' => 'boolean',
    ];

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'model');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EnquireAnswer::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function billing(): HasOne
    {
        return $this->hasOne(Billing::class);
    }
}