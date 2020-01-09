<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static $cached = [];

    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    public function __get($key)
    {
        return parent::__get($key) ?? self::fetchValue($key);
    }

    public static function fetchValue($key)
    {
        return self::$cached[$key] ?? (self::$cached[$key] = self::where('key', $key)->first()->value ?? null);
    }
}
