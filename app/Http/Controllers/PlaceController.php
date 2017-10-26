<?php

namespace App\Http\Controllers;

use App\Place;

class PlaceController extends Controller
{
    /**
     * Show all places.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('places.index');
    }

    /**
     * Show create form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('places.create');
    }

    /**
     * Show place profile.
     *
     * @param  Place $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        $place->recordVisit();

        return view('places.show', compact('place'));
    }
}
