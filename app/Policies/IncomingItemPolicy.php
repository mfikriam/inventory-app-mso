<?php

namespace App\Policies;

use App\Models\IncomingItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncomingItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     return $user->is_admin == 1;
    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IncomingItem $incomingItem): bool
    {
        return $user->is_admin == 1;
    }

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
    // public function update(User $user, IncomingItem $incomingItem): bool
    // {
    //     return $user->is_admin == 1;
    // }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IncomingItem $incomingItem): bool
    {
        return $user->is_admin == 1;
    }
}
