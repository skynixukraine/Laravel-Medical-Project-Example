<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    public function update($user, Setting $setting): bool
    {
        return $user instanceof User;
    }

    public function delete($user, Setting $setting): bool
    {
        return !in_array($setting->key, Setting::SYSTEM_SETTINGS, true);
    }

    public function view($user, Setting $setting): bool
    {
        return true;
    }

    public function create($user): bool
    {
        return $user instanceof User;
    }
}
