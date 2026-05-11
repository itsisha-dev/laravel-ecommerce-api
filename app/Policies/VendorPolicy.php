<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    /**
     * Create a new policy instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    public function create(User $user): bool
    {
        return $user->isVendor();
    }

    public function update(User $user, Vendor $vendor): bool
    {
        return $user->id === $vendor->user_id || $user->isAdmin();
    }
}
