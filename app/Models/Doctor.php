<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\DoctorCreated;
use App\Events\DoctorUpdated;
use App\Facades\Image;
use App\Facades\ImageIntervention;
use App\Notifications\DoctorVerifyEmail;
use App\Notifications\DoctorRequestedResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
 * @property string|null short_description
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
class Doctor extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use Notifiable;

    public const STATUS_CREATED = 'CREATED';
    public const STATUS_ACTIVATION_REQUESTED = 'ACTIVATION_REQUESTED';
    public const STATUS_ACTIVATED = 'ACTIVATED';
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_DEACTIVATED = 'DEACTIVATED';
    public const STATUS_CLOSED = 'CLOSED';

    const WIDTH_PHOTO = 285;
    const WIDTH_CERTS = 235;

    protected $dispatchesEvents = [
        'updated' => DoctorUpdated::class,
        'created' => DoctorCreated::class,
    ];

    protected $fillable = [
        'photo', 'title_id', 'phone_number', 'board_certification', 'medical_degree',
        'first_name', 'last_name', 'description', 'short_description', 'email', 'status', 'password',
        'region_id', 'specialization_id', 'stripe_account_id', 'email_verified_at'
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

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new DoctorVerifyEmail());
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->saveOrFail();
    }

    public function activeted(): bool
    {
        return $this->forceFill(['status' => self::STATUS_ACTIVATED])->saveOrFail();
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
            'title_id', 'phone_number', 'board_certification', 'medical_degree',
            'languages', 'last_name', 'description', 'email', 'status', 'password', 'first_name',
            'email_verified_at', 'specialization', 'stripe_account_id'
        ];

        foreach ($requiredAttributes as $attribute) {
            if (blank($this->{$attribute})) {
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
        $this->removePhotoFile();

        $this->attributes['photo'] = $value instanceof UploadedFile
            ? Storage::saveDoctorsPhoto(ImageIntervention::makeThumb($value, self::WIDTH_PHOTO))
            : $value;
    }

    public function setMedicalDegreeAttribute($value)
    {
        $this->removeMedicalDegreeFile();

        $this->attributes['medical_degree'] = $value instanceof UploadedFile
            ? Storage::saveDoctorsMedicalDegree(ImageIntervention::makeThumb($value, self::WIDTH_CERTS))
            : $value;
    }

    public function setBoardCertificationAttribute($value)
    {
        $this->removeBoardCertificationFile();

        $this->attributes['board_certification'] = $value instanceof UploadedFile
            ? Storage::saveDoctorsBoardCertification(ImageIntervention::makeThumb($value, self::WIDTH_CERTS))
            : $value;
    }

    public function removeBoardCertificationFile()
    {
        if ($this->board_certification) {
            Storage::removeFile($this->board_certification);
        }
    }

    public function removeMedicalDegreeFile()
    {
        if ($this->medical_degree) {
            Storage::removeFile($this->medical_degree);
        }
    }

    public function removePhotoFile()
    {
        if ($this->photo) {
            Storage::removeFile($this->photo);
        }
    }
}