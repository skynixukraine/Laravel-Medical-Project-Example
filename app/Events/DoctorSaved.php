<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Doctor;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class DoctorSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $doctor;

    public function __construct(Doctor $doctor)
    {
        $this->doctor = $doctor;
    }

    public function getDoctor(): Doctor
    {
        return $this->doctor;
    }
}
