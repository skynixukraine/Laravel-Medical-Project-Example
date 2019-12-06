<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user(admin) can view the user(doctor).
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $doctor
     * @return mixed
     */
    public function view(User $user, User $doctor)
    {
        return true;
    }

    /**
     * Determine whether the user can create doctors (users).
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the user (doctor).
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $doctor
     * @return mixed
     */
    public function update(User $user, User $doctor)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the user (doctor).
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the user (doctor).
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function restore(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the user (doctor).
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        return false;
    }
}
