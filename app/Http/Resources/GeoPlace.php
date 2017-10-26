<?php

namespace App\Http\Resources;

use App\Path;
use Illuminate\Http\Resources\Json\Resource;

class GeoPlace extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'address' => $this->address,
            'country' => $this->country,
            'description' => $this->description,
            'geometry' => ['location' => [
                'lng' => (float) $this->longitude,
                'lat' => (float) $this->latitude,
            ]],
            'image' => Path::absolute($this->image),
        ];
    }
}
