<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name'];
}