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
use Illuminate\Support\Carbon;

/**
 * Class Enquire
 * @package App\Models
 *
 * @property int id
 * @property string first_name
 * @property string last_name
 * @property string gender
 * @property Carbon date_of_birth
 * @property Carbon created_at
 * @property Carbon|null conclusion_created_at
 * @property Carbon updated_at
 * @property string phone_number
 * @property string email
 * @property string|null conclusion
 * @property string status
 * @property int doctor_id
 * @property string authy_id
 * @property Location location
 * @property EnquireAnswer[] answers
 * @property Doctor doctor
 * @property Billing billing
 * @property Message[] messages
 */
class Enquire extends Model
{
    use Notifiable, Authenticatable;

    public const STATUS_UNREAD = 'UNREAD';
    public const STATUS_READ = 'READ';
    public const STATUS_ARCHIVED = 'ARCHIVED';

    public const GENDER_MALE = 'MALE';
    public const GENDER_FEMALE = 'FEMALE';

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'date_of_birth', 'phone_number',
        'email', 'doctor_id', 'status', 'conclusion', 'authy_id', 'conclusion_created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'conclusion_created_at' => 'datetime',
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

    public function isConclusionExpired(): bool
    {
        return $this->conclusion_created_at->addWeek(6)->lessThanOrEqualTo(now());
    }
}