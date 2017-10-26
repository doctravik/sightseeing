<?php

namespace App\Http\Controllers\Api;

use App\Photo;
use App\Place;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\SearchPlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use Phaza\LaravelPostgis\Geometries\Point;
use App\Http\Resources\Place as PlaceResource;

class PlaceController extends Controller
{
    /**
     * Create new instance of PlaceController.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'store', 'show');
    }

    /**
     * Get places.
     *
     * @return array
     */
    public function index(SearchPlaceRequest $request)
    {
        return PlaceResource::collection(
            Place::with('author', 'users')
                ->filter()
                ->orderBy('visits_count', 'desc')
                ->orderBy('name')->paginate(2)
        );
    }

    /**
     * Store place in the db.
     *
     * @param  StorePlaceRequest $form
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePlaceRequest $form)
    {
        $place = $form->persist();

        if ($image = $form->file('image')) {
            $place->addPhoto(
                Photo::create($image)->resize()->save()
            );
        }

        return response()->json(['flash' => 'Place was successfully created'], 201);
    }

    /**
     * Update place in the db.
     *
     * @param  StorePlaceRequest $form
     * @param  Place $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePlaceRequest $request, Place $place)
    {
        $this->authorize('update', $place);

        $place->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'name' => $request->name,
            'address' => $request->address,
            'country' => $request->country,
            'description' => $request->description,
            'location' => new Point($request->latitude, $request->longitude),
        ]);

        return response()->json(['flash' => 'Place was successfully updated'], 200);
    }

    /**
     * Get place profile.
     *
     * @param  Place $place
     * @return array
     */
    public function show(Place $place)
    {
        return new PlaceResource($place);
    }

    /**
     * Destroy Place.
     *
     * @param  Place $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Place $place)
    {
        $this->authorize('delete', $place);

        $place->delete();

        optional($place->visits())->delete();

        return response()->json(['flash' => 'Place was successfully deleted'], 200);
    }
}
