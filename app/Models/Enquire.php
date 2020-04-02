<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\EnquireUpdated;
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
 * @property boolean is_seen
 * @property Carbon|null last_contacted_at
 */
class Enquire extends Model
{
    use Notifiable;

    public const STATUS_NEW = 'NEW';
    public const STATUS_WAIT_PATIENT_RESPONSE = 'AWAITING_PATIENT_RESPONSE';
    public const STATUS_WAIT_DOCTOR_RESPONSE = 'AWAITING_DOCTOR_RESPONSE';
    public const STATUS_RESOLVED = 'RESOLVED';
    public const STATUS_ARCHIVED = 'ARCHIVED';

    public const PAYMENT_STATUS_PENDING = 'PENDING';
    public const PAYMENT_STATUS_PAID = 'PAID';
    public const PAYMENT_STATUS_FAIL = 'FAIL';


    public const GENDER_MALE = 'MALE';
    public const GENDER_FEMALE = 'FEMALE';

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'date_of_birth', 'phone_number', 'is_seen',
        'email', 'doctor_id', 'status', 'conclusion', 'authy_id', 'conclusion_created_at',
        'last_contacted_at', 'payment_status', 'hash'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'conclusion_created_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_seen' => 'boolean'
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

    public function token(): HasOne
    {
        return $this->hasOne(EnquireToken::class);
    }

    public function isConclusionExpired(): bool
    {
        return $this->conclusion_created_at->addWeek(6)->lessThanOrEqualTo(now());
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->saveOrFail();
    }
}