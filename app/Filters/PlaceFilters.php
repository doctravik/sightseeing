<?php

namespace App\Filters;

use App\User;
use App\Geo\Postgis\NearestPlacesQuery;

class PlaceFilters extends Filters
{
    /**
     * Allowed filters.
     *
     * @var array
     */
    protected $filters = ['favorites', 'latitude', 'country', 'my', 'author', 'name', 'sort'];

    /**
     * Get places liked by signed user.
     *
     * @return Builder
     */
    public function favorites()
    {
        if (auth()->check()) {
            return $this->builder->select('places.*')
                ->join('favorites', 'places.id', '=', 'favorites.place_id')
                ->where('favorites.user_id', auth()->id());
        } else {
            return $this->builder;
        }
    }

    /**
     * Coordinates filter.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function latitude()
    {
        return (new NearestPlacesQuery(
            $this->builder,
            request('latitude'),
            request('longitude'),
            request('distance')
        ))->execute();
    }

    /**
     * Country filter.
     *
     * @param  string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function country($name)
    {
        return $this->builder->whereCountry($name);
    }

    /**
     * Name filter.
     *
     * @param  string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function name($name)
    {
        return $this->builder->where('name', 'like', "%$name%");
    }

    /**
     * Sort filter.
     *
     * @param  string $attribute
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sort($attribute)
    {
        if ($attribute == 'popular') {
            return $this->builder->orderBy('visits_count', 'desc')->orderBy('name');
        }

        return $this->builder->orderBy($attribute, 'asc')->orderBy('name');
    }

    /**
     * Filter by author of the place.
     *
     * @param  string $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function author($slug)
    {
        if ($user = User::whereSlug($slug)->first()) {
            return $this->builder->where('user_id', $user->id);
        }

        return $this->builder;
    }

    /**
     * Get only places belonged to the authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function my()
    {
        if (auth()->check()) {
            return $this->builder->where('user_id', auth()->id());
        }

        return $this->builder;
    }
}
