<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static $cached = [];

    public const SYSTEM_SETTINGS = [
        'display_enquire_price', 'enquire_total_price', 'enquire_price_currency',
        'enquire_admins_fee', 'enquire_charge_description'
    ];

    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    public function __get($key)
    {
        return parent::__get($key) ?? self::fetchValue($key);
    }

    public static function fetchValue($key, $default = null)
    {
        return self::$cached[$key] ?? (self::$cached[$key] = self::where('key', $key)->first()->value ?? $default);
    }
}
