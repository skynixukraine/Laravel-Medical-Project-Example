<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class Doctor extends Model implements CanResetPassword
{
    use Notifiable, HasApiTokens, Authenticatable;

    public const PHOTOS_DIR = 'public/doctors/';

    protected $fillable = [
        'photo',
        'prefix',
        'first_name',
        'last_name',
        'description',
        'email',
        'is_active',
        'password',
        'region_id',
        'location_id'
    ];

    protected $hidden = ['password'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class);
    }

    public function uploadPhoto(UploadedFile $file): void
    {
        $dir = self::PHOTOS_DIR . date('Y-m');
        $name = Str::slug($this->first_name . '-' . $this->last_name);

        while (Storage::exists($dir . '/' . $name . '.' . $file->extension())) {
            $name .= random_int(0, 9);
        }

        $this->photo = $file->storeAs($dir, $name . '.' . $file->extension());
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
}