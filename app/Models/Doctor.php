<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Doctor extends Model
{
    use Notifiable;

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

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
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
}