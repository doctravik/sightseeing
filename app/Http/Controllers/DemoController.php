<?php

namespace App\Http\Controllers;

use App\Demo\Demo;

class DemoController extends Controller
{
    /**
     * Login demo account.
     *
     * @return [type]
     */
    public function login()
    {
        if (Demo::login()) {
            return redirect()->intended('home');
        }

        return redirect('/login');
    }
}
