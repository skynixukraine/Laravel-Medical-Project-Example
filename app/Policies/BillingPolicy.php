<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Billing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillingPolicy
{
    use HandlesAuthorization;

    public function create($user): bool
    {
        return false;
    }

    public function view($user, Billing $billing)
    {
        if ($user instanceof User) {
            return true;
        }

        return $billing->enquire->doctor_id === $user->id;
    }
}
