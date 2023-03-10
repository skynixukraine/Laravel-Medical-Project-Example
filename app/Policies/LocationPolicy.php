<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Enquire;
use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    public function update($user, Location $location): bool
    {
        return $location->model_type !== Enquire::class;
    }

    public function view($user, Location $location): bool
    {
        return true;
    }

    public function create($user): bool
    {
        return $user instanceof User;
    }
}
