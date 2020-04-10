<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contact extends Model
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'body'];

    public function routeNotificationFor()
    {
        return $this->email;
    }
}
