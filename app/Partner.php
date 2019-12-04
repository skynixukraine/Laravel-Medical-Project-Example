<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name', 'partner_id'
    ];

    public function submission()
    {
        return $this->hasMany('App\Submission', 'partner_id');
    }

    public static function findByPartnerId($partnerID) {
        return self::where('partner_id', $partnerID)->first();
    }

}
