<?php

namespace App\Observers;

use App\Place;

class PlaceObserver
{
    public function deleted(Place $place)
    {
        $place->deletePhoto();
    }
}
