<?php

declare(strict_types=1);

namespace App\Events;

use App\Traits\DoctorInfo;
use Illuminate\Queue\SerializesModels;

class DoctorChangedEmail
{
    use DoctorInfo, SerializesModels;
}