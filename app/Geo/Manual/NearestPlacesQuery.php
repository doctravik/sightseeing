<?php

namespace App\Geo\Manual;

use App\Place;
use App\Contracts\Query;

class NearestPlacesQuery implements Query
{
    /**
     * Earth radius in kilometers.
     */
    const EARTH_RADIUS = 6371;

    /**
     * Kilometers in one degree of latitude.
     */
    const LATITUDE_DEGREE = 111.045;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * Search radius in kilometers.
     *
     * @var int
     */
    protected $distance;

    /**
     * Create new isntance of NearestPlacesQuery.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $distance
     */
    public function __construct($latitude, $longitude, $distance)
    {
        $this->latitude = (float) $latitude;
        $this->longitude = (float) $longitude;
        $this->distance = (int) $distance;
    }

    /**
     * Execute query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function execute()
    {
        return Place::select('places.*', 'distance')
            ->join($this->distanceQuery(), 'temp.id', '=', 'places.id')
            ->addBinding([
                deg2rad($this->latitude),
                deg2rad($this->latitude),
                deg2rad($this->longitude),
                self::EARTH_RADIUS,
            ])
            ->whereBetween('latitude', [$this->getMinLatitude(), $this->getMaxLatitude()])
            ->whereBetween('longitude', [$this->getMinLongitude(), $this->getMaxLongitude()])
            ->where('distance', '<=', $this->distance);
    }

    /**
     * Subquery to define the distance between two points.
     *
     * @return \Illuminate\Database\Query\Expression
     */
    protected function distanceQuery()
    {
        return  \DB::raw("(
            SELECT id, (acos(sin(?) * sin(radians(latitude)) + cos(?) * cos(radians(latitude)) * cos(? - radians(longitude))) * ?)
            AS distance
            FROM places
            ) AS temp
        ");
    }

    /**
     * Get min latitude searching bound.
     *
     * @return float latitude (degree)
     */
    protected function getMinLatitude()
    {
        return $this->latitude - $this->getLatitudeDelta();
    }

    /**
     * Get max latitude searching bound.
     *
     * @return float latitude (degree)
     */
    protected function getMaxLatitude()
    {
        return $this->latitude + $this->getLatitudeDelta();
    }

    /**
     * Get min longitude searching bound.
     *
     * @return float longitude (degree)
     */
    protected function getMinLongitude()
    {
        return $this->longitude - $this->getLongitudeDelta();
    }

    /**
     * Get max longitude searching bound.
     *
     * @return float longitude (degree)
     */
    protected function getMaxLongitude()
    {
        return $this->longitude + $this->getLongitudeDelta();
    }

    /**
     * Define distance in degree from the given point that confine searching within latitude.
     *
     * @return float
     */
    protected function getLatitudeDelta()
    {
        return $this->distance / self::LATITUDE_DEGREE;
    }

    /**
     * Define distance in degree from the given point that confine searching within longitude.
     *
     * @return float
     */
    protected function getLongitudeDelta()
    {
        return $this->distance / abs(cos(deg2rad($this->latitude)) * self::LATITUDE_DEGREE);
    }
}
