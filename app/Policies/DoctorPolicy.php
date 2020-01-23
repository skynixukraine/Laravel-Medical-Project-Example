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
        return $doctor->status === Doctor::STATUS_ACTIVATED || $user->is($doctor);
    }

    public function update(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor);
    }

    public function activate(Doctor $user, Doctor $doctor): bool
    {
        if (!$user->is($doctor)) {
            return false;
        }

        return $doctor->canBeApproved();
    }

    public function close(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor);
    }

    public function stripeConnect(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor);
    }

    public function stripeToken(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor);
    }
}
