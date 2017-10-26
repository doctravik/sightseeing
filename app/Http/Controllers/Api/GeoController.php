<?php

namespace App\Http\Controllers\Api;

use App\Place;
use App\Http\Controllers\Controller;
use Phaza\LaravelPostgis\Geometries\Point;
use App\Http\Requests\SearchPlaceOnMapRequest;
use App\Http\Resources\GeoPlace as PlaceResource;

class GeoController extends Controller
{
    /**
     * Get the nearest places to the point within the given radius (distance) in km.
     *
     * @return array
     */
    public function index(SearchPlaceOnMapRequest $request)
    {
        $places = Place::filter()->get();

        return PlaceResource::collection($places);
    }
}
