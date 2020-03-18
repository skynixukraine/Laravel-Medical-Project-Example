<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    public static $cached = [];

    public $timestamps = false;

    protected $fillable = ['name', 'title', 'country'];

    public function __get($key)
    {
        return parent::__get($key) ?? self::fetchValue($key);
    }

    public static function fetch($key)
    {
        return self::$cached[$key] ?? (self::$cached[$key] = self::where('name', $key)->first());
    }
}
