<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gender', 'title', 'first_name', 'last_name',
        'birthday', 'birthplace',
        'street', 'zip', 'city', 'country', // 'lat', 'lng',
        'email', 'phone', 'password',
        'graduation_year', 'reason_for_application',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'status'
    ];

    // needed for nova
    protected $casts = [
        'birthday' => 'date'
    ];

    public static function generateUserID()
    {
        do {
            $randomStr = strtolower(str_random(10));
            $existing = self::where('user_id', $randomStr)->first();
        } while ($existing);
        return $randomStr;
    }

    public static function generatePhotoName()
    {
        do {
            $randomStr = strtolower(str_random(10));
            $existing = self::where('photo', $randomStr)->first();
        } while ($existing);
        return $randomStr;
    }

    public function assignedSubmissions()
    {
        return $this->hasMany('App\Models\Submission', 'assigned_to_user_id');
    }

    public function answeredSubmissions()
    {
        return $this->assignedSubmissions()->where('status', 'answered');
    }

    public function findForPassport($username) {
        return $this->where('email', $username)
            ->where('status', 'confirmed')->first();
    }

    public function getNameAttribute($value)
    {
        return $this->name();
    }

    public function getPhotoUrl() {
        $url = config('app.MIX_AERZTEPHOTOS_FOLDER');
        if (!$this->photo) {
            return $url . '/no_photo.jpg';
        }
        else {
            return $url . '/' . $this->photo . '.jpg';
        }
    }

    public function name() {
        $title = ($this->title) ?  $this->title . " " : "";
        return $title . $this->first_name . " " . $this->last_name;
    }

}
