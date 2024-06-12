<?php

namespace App\Policies;

use App\Models\Datel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DatelPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Datel $datel): bool
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Datel $datel): bool
    {
        return $user->is_admin == 1;
    }
}
