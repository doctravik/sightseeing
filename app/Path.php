<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class Path
{
    public static function absolute($path)
    {
        return $path ? Storage::url($path) : null;
    }
}
