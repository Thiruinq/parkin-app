<?php

namespace App\Policies;

use App\Models\AuthUser;
use App\Models\PersonalDetail;
use Illuminate\Auth\Access\Response;

class PersonalDetailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(AuthUser $authUser): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(AuthUser $authUser, PersonalDetail $personalDetail): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(AuthUser $authUser): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(AuthUser $authUser, PersonalDetail $personalDetail): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(AuthUser $authUser, PersonalDetail $personalDetail): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(AuthUser $authUser, PersonalDetail $personalDetail): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(AuthUser $authUser, PersonalDetail $personalDetail): bool
    {
        //
    }
}
