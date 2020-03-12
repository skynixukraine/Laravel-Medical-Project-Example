<?php

declare(strict_types=1);

namespace App\Events;

class DoctorDeleted
{
    /** @var array  */
    private $doctor;

    public function __construct(array $doctor)
    {
        $this->doctor = $doctor;
    }

    public function getDoctor(): array
    {
        return $this->doctor;
    }
}
