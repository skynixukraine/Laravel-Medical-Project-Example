<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    public $timestamps = false;

    protected $hidden = [
        'pivot'
    ];

    protected $fillable = [
        'name'
    ];

    public function Submission()
    {
        return $this->belongsToMany('App\Submission');
    }

}
