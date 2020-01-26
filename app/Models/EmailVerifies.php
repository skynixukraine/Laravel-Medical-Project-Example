<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerifies extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['email', 'token'];
}