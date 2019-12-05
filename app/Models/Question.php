<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question', 'answer', 'submission_id', 'asked_by_user_id'
    ];

    public function askedBy()
    {
        return $this->belongsTo('App\Models\User', 'asked_by_user_id');
    }

    public function submission()
    {
        return $this->belongsTo('App\Models\Submission', 'submission_id');
    }




}
