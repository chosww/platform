<?php

namespace App\Policies;

use App\Models\IndigenousIdentity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IndigenousIdentityPolicy
{
    use HandlesAuthorization;

    public function before(User $user): null|bool
    {
        return $user->isAdministrator() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

//    public function create(User $user): bool
//    {
//        return false;
//    }
//
//    public function update(User $user, IndigenousIdentity $indigenousIdentity): bool
//    {
//        return false;
//    }
//
//    public function delete(User $user, IndigenousIdentity $indigenousIdentity): bool
//    {
//        return false;
//    }
}
