<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Doctor;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoctorPolicy
{
    use HandlesAuthorization;

    public function view(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor);
    }

    public function update(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor);
    }
}
