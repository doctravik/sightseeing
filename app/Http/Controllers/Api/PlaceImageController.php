<?php

namespace App\Http\Controllers\Api;

use App\Path;
use App\Photo;
use App\Place;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;

class PlaceImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Upload image for the Place.
     *
     * @param  UploadImageRequest $request
     * @param  Place $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UploadImageRequest $request, Place $place)
    {
        $this->authorize('update', $place);

        $place->deletePhoto();

        $image = $request->image ? Photo::create($request->image)->resize()->save() : null;

        $place->addPhoto($image);

        return response()->json([
            'image' => Path::absolute($image),
            'flash' => 'Image was successfully updated',
        ], 200);
    }

    /**
     * Remove image of the Place.
     *
     * @param  Place $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Place $place)
    {
        $this->authorize('delete', $place);

        $place->deletePhoto();

        $place->addPhoto(null);

        return response()->json([
            'flash' => 'Image was successfully deleted',
        ], 200);
    }
}
