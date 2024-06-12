<?php

namespace App\Policies;

use App\Models\StatusExdismentie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusExdismentiePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin == 1;
    }
    public function create(User $user): bool
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StatusExdismentie $statusExdismentie): bool
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StatusExdismentie $statusExdismentie): bool
    {
        return $user->is_admin == 1;
    }
}
