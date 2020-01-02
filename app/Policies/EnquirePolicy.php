<?php

namespace App\Policies;

use App\Models\Enquire;
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
        return true;
    }
}
