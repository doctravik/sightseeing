<?php

namespace App\Favorites;

use App\Place;

trait HasFavorites
{
    /**
     * Favorites places of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany;
     */
    public function favorites()
    {
        return $this->belongsToMany(Place::class, 'favorites', 'user_id', 'place_id');
    }

    /**
     * Check if the user has liked the place.
     *
     * @param  Place $place
     * @return boolean
     */
    public function hasLiked(Place $place)
    {
        return $this->favorites()->where('place_id', $place->id)->exists();
    }

    /**
     * Add place to the user's favorite list.
     *
     * @param  Place $place
     * @return void
     */
    public function like(Place $place)
    {
        $this->favorites()->attach($place);
    }

    /**
     * Remove place from the user's favorite list.
     *
     * @param  Place $place
     * @return void
     */
    public function unlike(Place $place)
    {
        $this->favorites()->detach($place);
    }
}
