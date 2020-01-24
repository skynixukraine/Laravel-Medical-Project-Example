<?php

declare(strict_types=1);

namespace App\Models;

use App\Notifications\DoctorVerifyEmail;
use Illuminate\Auth\Authenticatable;
use App\Notifications\DoctorRequestedResetPassword;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessTokenResult;
use Throwable;

/**
 * Class Doctor
 * @package App\Models
 *
 * @property int id
 * @property string|null photo
 * @property string|null board_certification
 * @property string|null medical_degree
 * @property string|null title
 * @property string|null first_name
 * @property string|null last_name
 * @property string|null description
 * @property string phone_number
 * @property string email
 * @property string status
 * @property string password
 * @property int|null region_id
 * @property int|null stripe_account_id
 * @property Region|null region
 * @property Specialization|null specialization
 * @property Language[] languages
 * @property Location|null location
 * @property Carbon email_verified_at
 */
class Doctor extends Model implements CanResetPassword, MustVerifyEmail
{
    use Notifiable, HasApiTokens, Authenticatable, MustVerifyEmailTrait;

    public const STATUS_CREATED = 'CREATED';
    public const STATUS_ACTIVATION_REQUESTED = 'ACTIVATION_REQUESTED';
    public const STATUS_ACTIVATED = 'ACTIVATED';
    public const STATUS_DEACTIVATED = 'DEACTIVATED';
    public const STATUS_CLOSED = 'CLOSED';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo', 'title', 'phone_number', 'board_certification', 'medical_degree',
        'first_name', 'last_name', 'description', 'email', 'status', 'password',
        'region_id', 'specialization_id', 'stripe_account_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'model');
    }

    public function enquires(): HasMany
    {
        return $this->hasMany(Enquire::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class);
    }

    public function billings(): HasManyThrough
    {
        return $this->hasManyThrough(Billing::class, Enquire::class);
    }

    /**
     * @inheritDoc
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new DoctorRequestedResetPassword($token));
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     * @throws Throwable
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->saveOrFail();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new DoctorVerifyEmail());
    }

    public function saveToken(): PersonalAccessTokenResult
    {
        $token = $this->createToken('Personal Access Token');
        $token->token->expires_at = Passport::$tokensExpireAt;
        $token->token->saveOrFail();

        return $token;
    }

    public function canBeApproved()
    {
        $requiredAttributes = [
            'photo', 'title', 'phone_number', 'board_certification', 'medical_degree', 'location', 'languages',
            'last_name', 'description', 'email', 'status', 'password', 'first_name', 'email_verified_at', 'specialization'
        ];

        foreach ($requiredAttributes as $attribute) {
            if (blank($this->{$attribute})) {
                return false;
            }
        }

        foreach ($this->location->getFillable() as $attribute) {
            if (blank($this->location->{$attribute})) {
                return false;
            }
        }

        return true;
    }
}