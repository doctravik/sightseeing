<?php

namespace App\Http\Controllers\Api;

use App\Place;
use App\Http\Resources\Place as PlaceResource;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    /**
     * Create a new instance of FavoriteController.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get all favorites of the user.
     *
     * @return array.
     */
    public function index()
    {
        return PlaceResource::collection(
            auth()->user()->favorites()->get()
        );
    }

    /**
     * Save place to the user's favorite list.
     *
     * @param  Place $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Place $place)
    {
        if ($place->isLikedBy($user = auth()->user())) {
            return response()->json(['flash' => 'Place is already in your favorites.'], 200);
        } else {
            $user->like($place);

            return response()->json(['flash' => 'Place was successfully added to your favorites'], 201);
        }
    }

    /**
     * Remove place to the user's favorite list.
     *
     * @param  Place $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Place $place)
    {
        auth()->user()->unlike($place);

        return response()->json(['flash' => 'Place was successfully deleted from your favorites'], 200);
    }
}
