<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'key';

    protected $fillable = ['key', 'value'];

    public function getRouteKeyName()
    {
        return 'key';
    }
}
