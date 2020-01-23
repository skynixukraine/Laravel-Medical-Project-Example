<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\Enquire;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnquirePolicy
{
    use HandlesAuthorization;

    public function create($user): bool
    {
        return $user === null;
    }

    public function view($user, Enquire $enquire)
    {
        if ($user instanceof User) {
            return true;
        }

        return $enquire->doctor_id === $user->id;
    }

    public function updateConclusion($user, Enquire $enquire): bool
    {
        return $user instanceof Doctor && $user->id === $enquire->doctor_id;
    }

    public function close($user, Enquire $enquire): bool
    {
        return $user instanceof Doctor && $user->id === $enquire->doctor_id && !blank($enquire->conclusion);
    }

    public function addMessage($user, Enquire $enquire): bool
    {
        return $user instanceof Doctor && $user->id === $enquire->doctor_id;
    }

    public function messages($user, Enquire $enquire): bool
    {
        return $user instanceof Doctor && $user->id === $enquire->doctor_id;
    }

    public function downloadConclusion($user, Enquire $enquire): bool
    {
        return $enquire->token && $enquire->token->expires_at->gte(now())
            && $enquire->conclusion_created_at && $enquire->conclusion_created_at->addWeek(6)->gte(now());
    }
}
