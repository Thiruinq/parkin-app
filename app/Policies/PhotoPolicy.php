<?php

namespace App\Policies;

use App\Models\AuthUser;
use App\Models\Photo;
use Illuminate\Auth\Access\Response;

class PhotoPolicy
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
    public function view(AuthUser $authUser, Photo $photo): bool
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
    public function update(AuthUser $authUser, Photo $photo): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(AuthUser $authUser, Photo $photo): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(AuthUser $authUser, Photo $photo): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(AuthUser $authUser, Photo $photo): bool
    {
        //
    }
}
