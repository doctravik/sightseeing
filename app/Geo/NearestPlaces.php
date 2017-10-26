<?php

namespace App\Geo;

use App\Geo\Postgis\NearestPlacesQuery;

class NearestPlaces
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * Search radius in kilometers.
     *
     * @var integer
     */
    protected $distance = 100;

    /**
     * Maximal number of places retrieved by one request.
     *
     * @var integer
     */
    protected $limit = 100;

    /**
     * Order of the NearPlace.
     *
     * @var array
     */
    protected $orderBy = [
        'attribute' => 'distance',
        'order' => 'asc'
    ];

    /**
     * Create instance of NearestPlaces.
     *
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($builder, $latitude, $longitude)
    {
        $this->builder = $builder;
        $this->latitude = $latitude;
        $this->longitude =  $longitude;
    }

    /**
     * Setter for the distance.
     *
     * @param  integer $distance
     * @return $this
     */
    public function within($distance)
    {
        $this->distance = (int) $distance;

        return $this;
    }

    /**
     * Setter for limits.
     *
     * @param  integer $limit
     * @return $this
     */
    public function take($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Setter for orderBy.
     *
     * @param  string $limit
     * @return $this
     */
    public function orderBy($attribute, $order = 'asc')
    {
        $this->orderBy = [
            'attribute' => $attribute,
            'order' => $order
        ];

        return $this;
    }

    /**
     * Get places that are within a certain distance from a point
     * given in spherical coordinates (latitude and longitude)
     *
     * @return \Illuminate\Database\Eloquent\Builder;
     */
    public function find()
    {
        return $this->builder()
            ->take($this->limit)
            ->orderBy($this->orderBy['attribute'], $this->orderBy['order'])
            ->get();
    }

    /**
     * Get instance of eloquent builder.
     *
     * @return \Illuminate\Http\Database\Eloquent
     */
    public function toBuilder()
    {
        return $this->builder();
    }

    /**
     * Build the base query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function builder()
    {
        return (new NearestPlacesQuery(
            $this->builder,
            $this->latitude,
            $this->longitude,
            $this->distance
        ))->execute();
    }
}
