<?php

namespace App\Policies;

use App\User;
use App\Submission;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the submission.
     *
     * @param  \App\User  $user
     * @param  \App\Submission  $submission
     * @return mixed
     */
    public function view(User $user, Submission $submission)
    {
        return true;
    }

    /**
     * Determine whether the user can create submissions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the submission.
     *
     * @param  \App\User  $user
     * @param  \App\Submission  $submission
     * @return mixed
     */
    public function update(User $user, Submission $submission)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the submission.
     *
     * @param  \App\User  $user
     * @param  \App\Submission  $submission
     * @return mixed
     */
    public function delete(User $user, Submission $submission)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the submission.
     *
     * @param  \App\User  $user
     * @param  \App\Submission  $submission
     * @return mixed
     */
    public function restore(User $user, Submission $submission)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the submission.
     *
     * @param  \App\User  $user
     * @param  \App\Submission  $submission
     * @return mixed
     */
    public function forceDelete(User $user, Submission $submission)
    {
        return false;
    }
}
