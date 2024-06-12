<?php

namespace App\Policies;

use App\Models\StatusPart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusPartPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function viewAny(User $user):bool
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
    public function update(User $user, StatusPart $statusPart): bool
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StatusPart $statusPart): bool
    {
        return $user->is_admin == 1;
    }
}
