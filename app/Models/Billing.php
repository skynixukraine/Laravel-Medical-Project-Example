<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billing extends Model
{
    protected $fillable = [
        'amount', 'currency', 'enquire_id', 'admin_fee',
        'invoice_1A_factor', 'invoice_1A_price',
        'invoice_5A_factor', 'invoice_5A_price',
        'invoice_75A_factor', 'invoice_75A_price',
    ];

    public function enquire(): BelongsTo
    {
        return $this->belongsTo(Enquire::class);
    }

    public function getInvoice1AFactorAttribute($value)
    {
        return $value / 100;
    }

    public function getInvoice1APriceAttribute($value)
    {
        return sprintf('%.2f', $value / 100);
    }

    public function getInvoice5AFactorAttribute($value)
    {
        return $value / 100;
    }

    public function getInvoice5APriceAttribute($value)
    {
        return sprintf('%.2f', $value / 100);
    }

    public function getInvoice75AFactorAttribute($value)
    {
        return $value / 100;
    }

    public function getInvoice75APriceAttribute($value)
    {
        return sprintf('%.2f', $value / 100);
    }
    
}
