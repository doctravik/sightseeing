<?php

namespace App\Http\Requests;

use App\Place;
use App\Rules\UniqueLocation;
use Phaza\LaravelPostgis\Geometries\Point;
use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address' => 'required|string',
            'name' => 'required|string|unique:places,name',
            'country' => 'required|string',
            'description' => 'sometimes|string|nullable',
            'image' => 'sometimes|mimes:jpg,jpeg,png|max:2048|nullable',
            'latitude' => 'required|numeric',
            'longitude' => [
                        'required',
                        'numeric',
                        new UniqueLocation($this->latitude, $this->longitude)
                    ]
        ];
    }

    /**
     * Persist form attribute to the Place.
     *
     * @return Place
     */
    public function persist()
    {
        return Place::create([
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'name' => $this->name,
            'address' => $this->address,
            'country' => $this->country,
            'description' => $this->description,
            'user_id' => auth()->check() ? auth()->id() : null,
            'location' => new Point($this->latitude, $this->longitude),
        ]);
    }
}
