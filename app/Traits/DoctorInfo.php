<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Doctor;

trait DoctorInfo
{
    /**
     * @var Doctor
     */
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
