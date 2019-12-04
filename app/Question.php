<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question', 'answer', 'submission_id', 'asked_by_user_id'
    ];

    public function askedBy()
    {
        return $this->belongsTo('App\User', 'asked_by_user_id');
    }

    public function submission()
    {
        return $this->belongsTo('App\Submission', 'submission_id');
    }




}
