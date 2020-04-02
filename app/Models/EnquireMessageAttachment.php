<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EnquireMessageAttachment extends Model
{
    protected $fillable = ['path'];

    public function enquireMessage(): BelongsToMany
    {
        return $this->belongsToMany(EnquireMessage::class);
    }
}
