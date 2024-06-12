<?php

namespace App\Policies;

use App\Models\DeliveryType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeliveryTypePolicy
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
    public function update(User $user, DeliveryType $deliveryType): bool
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DeliveryType $deliveryType): bool
    {
        return $user->is_admin == 1;
    }
}
