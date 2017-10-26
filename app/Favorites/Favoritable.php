<?php

namespace App\Favorites;

use App\User;

trait Favoritable
{
    /**
     * Users who marked place as a favorite.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany;
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'place_id', 'user_id');
    }

    /**
     * Whether user like the place.
     *
     * @param  User $user
     * @return boolean
     */
    public function isLikedBy(User $user)
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }
}
