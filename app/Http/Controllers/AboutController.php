<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    /**
     * Show about page.
     *
     * @return \Illumminate\Http\Response
     */
    public function index()
    {
        return view('about');
    }
}
