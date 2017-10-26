<?php

namespace App\Policies;

use App\User;
use App\Place;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlacePolicy
{
    use HandlesAuthorization;

    /**
     * Authorize all actions within a given policy if the user is admin.
     *
     * @param  User $user [description]
     * @param  string $ability [description]
     * @return boolean|void
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the given place can be updated by the user.
     *
     * @param  User $user
     * @param  Place $place
     * @return boolean
     */
    public function update(User $user, Place $place)
    {
        return $place->user_id == $user->id;
    }

    /**
     * Determine if the given place can be updated by the user.
     *
     * @param  User $user
     * @param  Place $place
     * @return boolean
     */
    public function delete(User $user, Place $place)
    {
        return $place->user_id == $user->id;
    }
}
