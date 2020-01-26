<?php

declare(strict_types=1);

namespace App\Models;

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
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessTokenResult;
use App\Facades\Storage;

/**
 * Class Doctor
 * @package App\Models
 *
 * @property int id
 * @property string|null photo
 * @property string|null board_certification
 * @property string|null medical_degree
 * @property int|null title_id
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
 * @property DoctorTitle|null title
 * @property Carbon email_verified_at
 */
class Doctor extends Model implements CanResetPassword, MustVerifyEmail
{
    use Authenticatable;
    use HasApiTokens;
    use MustVerifyEmailTrait;
    use Notifiable;

    public const STATUS_CREATED = 'CREATED';
    public const STATUS_ACTIVATION_REQUESTED = 'ACTIVATION_REQUESTED';
    public const STATUS_ACTIVATED = 'ACTIVATED';
    public const STATUS_DEACTIVATED = 'DEACTIVATED';
    public const STATUS_CLOSED = 'CLOSED';

    protected $fillable = [
        'photo', 'title_id', 'phone_number', 'board_certification', 'medical_degree',
        'first_name', 'last_name', 'description', 'email', 'status', 'password',
        'region_id', 'specialization_id', 'stripe_account_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function title(): BelongsTo
    {
        return $this->belongsTo(DoctorTitle::class);
    }

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

    public function emailVerify()
    {
        return $this->morphOne(EmailVerifies::class, 'model')->orderByDesc('created_at');
    }

    public function getEmailForPasswordReset(): string
    {
        return $this->email;
    }

    public function sendPasswordResetNotification($token): void 
    {
        $this->notify(new DoctorRequestedResetPassword($token));
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->saveOrFail();
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
            'photo', 'title_id', 'phone_number', 'board_certification', 'medical_degree', 'location', 'languages',
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] =  Hash::make($value);
    }

    public function setPhotoAttribute($value)
    {
        if ($this->photo) {
            Storage::removeFile($this->photo);
        }

        $this->attributes['photo'] = $value instanceof UploadedFile
            ? Storage::saveDoctorsPhoto($value)
            : $value;
    }

    public function setMedicalDegreeAttribute($value)
    {
        if ($this->medical_degree) {
            Storage::removeFile($this->medical_degree);
        }

        $this->attributes['medical_degree'] = $value instanceof UploadedFile
            ? Storage::saveDoctorsMedicalDegree($value)
            : $value;
    }

    public function setBoardCertificationAttribute($value)
    {
        if ($this->board_certification) {
            Storage::removeFile($this->board_certification);
        }

        $this->attributes['board_certification'] = $value instanceof UploadedFile
            ? Storage::saveDoctorsBoardCertification($value)
            : $value;
    }
}