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
        return $doctor->status === Doctor::STATUS_APPROVED || $user->is($doctor);
    }

    public function update(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor);
    }

    public function requestActivation(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor) && $doctor->canBeApproved()
            && ($doctor->status === Doctor::STATUS_ACTIVATED || $doctor->status === Doctor::STATUS_ACTIVATION_REQUESTED);
    }

    public function close(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor);
    }

    public function delete(Doctor $user, Doctor $doctor): bool
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

    public function deactivate(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor)
               && ($doctor->status === Doctor::STATUS_ACTIVATED || $doctor->status === Doctor::STATUS_APPROVED);
    }

    public function activate(Doctor $user, Doctor $doctor): bool
    {
        return $user->is($doctor) && $doctor->status === Doctor::STATUS_DEACTIVATED;
    }
}
