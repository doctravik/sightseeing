<?php

namespace App\Http\Controllers;

class GeoController extends Controller
{
    /**
     * Show map with search form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('geo');
    }
}
