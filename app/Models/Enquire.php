<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasApiTokensWithName;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;

class Enquire extends Model
{
    use Notifiable, HasApiTokensWithName, Authenticatable;

    public const STATUS_UNREAD = 'UNREAD';
    public const STATUS_READ = 'READ';
    public const STATUS_ARCHIVED = 'ARCHIVED';

    public const GENDER_MALE = 'MALE';
    public const GENDER_FEMALE = 'FEMALE';

    private $tokenName = 'Enquire Access Token';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone_number',
        'email',
        'doctor_id',
        'status',
        'conclusion',
        'authy_id',
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

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function billing(): HasOne
    {
        return $this->hasOne(Billing::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(EnquireMessage::class);
    }
}