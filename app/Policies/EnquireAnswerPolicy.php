<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EnquireAnswer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnquireAnswerPolicy
{
    use HandlesAuthorization;

    public function create($user): bool
    {
        return false;
    }

    public function view($user, EnquireAnswer $answer)
    {
        if ($user instanceof User) {
            return true;
        }

        return $answer->enquire->doctor_id === $user->id;
    }
}
