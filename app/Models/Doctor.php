<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\DoctorSaving;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class Doctor
 * @package App\Models
 *
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
 * @property integer region_id
 * @property Region|null region
 * @property Specialization|null specialization
 * @property Language[] languages
 * @property Location|null location
 */
class Doctor extends Model implements CanResetPassword, MustVerifyEmail
{
    use Notifiable, HasApiTokens, Authenticatable, \Illuminate\Auth\MustVerifyEmail;

    public const STATUS_CREATED = 'CREATED';
    public const STATUS_ACTIVATION_REQUESTED = 'ACTIVATION_REQUESTED';
    public const STATUS_ACTIVATED = 'ACTIVATED';
    public const STATUS_DEACTIVATED = 'DEACTIVATED';
    public const STATUS_CLOSED = 'CLOSED';

    protected $fillable = [
        'photo',
        'title',
        'phone_number',
        'board_certification',
        'medical_degree',
        'first_name',
        'last_name',
        'description',
        'email',
        'status',
        'password',
        'region_id',
        'specialization_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    protected $hidden = ['password'];

    protected $dispatchesEvents = [
        'saving' => DoctorSaving::class,
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

    public function findForPassport($username)
    {
        return $this->whereEmail($username)->whereIsActive(true)->first();
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
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     * @throws \Throwable
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->saveOrFail();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
}