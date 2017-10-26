<?php

namespace App\Geo\Postgis;

use App\Contracts\Query;

class NearestPlacesQuery implements Query
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder $builder
     */
    protected $builder;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * Search radius in meters.
     *
     * @var int
     */
    protected $distance;

    /**
     * Create new isntance of NearestPlacesQuery.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param float $latitude
     * @param float $longitude
     * @param int $distance
     */
    public function __construct($builder, $latitude, $longitude, $distance)
    {
        $this->builder = $builder;
        $this->latitude = (float) $latitude;
        $this->longitude = (float) $longitude;
        $this->distance = (int) $distance * 1000;
    }

    /**
     * Execute query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function execute()
    {
        return $this->builder
            ->selectRaw("places.*, ST_Distance(ST_MakePoint(?, ?), location) as distance", [
                $this->longitude, $this->latitude
            ])->whereRaw("ST_DWithin(ST_MakePoint(?, ?), location, ?)", [
                $this->longitude,
                $this->latitude,
                $this->distance
            ]);
    }
}
