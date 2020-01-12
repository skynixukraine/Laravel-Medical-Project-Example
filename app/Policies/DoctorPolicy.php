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

        $requiredAttributes = [
            'photo', 'title', 'phone_number', 'board_certification', 'medical_degree', 'location', 'languages',
            'last_name', 'description', 'email', 'status', 'password', 'first_name', 'email_verified_at', 'specialization'
        ];

        foreach ($requiredAttributes as $attribute) {
            if (blank($doctor->{$attribute})) {
                return false;
            }
        }

        foreach ($doctor->location->getFillable() as $attribute) {
            if (blank($doctor->location->{$attribute})) {
                return false;
            }
        }

        return true;
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
