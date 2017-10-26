<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchPlaceOnMapRequest extends FormRequest
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
            'longitude' => 'required_with:latitude,distance|numeric',
            'latitude' => 'required_with:longitude,distance|numeric',
            'distance' => 'required_with:longitude|numeric',
            'country' => 'required_without:latitude,longitude|country'
        ];
    }

    /**
     * Custom error's messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'latitude.required_with' => 'The location field is required when distance is present.',
            'longitude.required_with' => 'The location field is required when distance is present.',
            'distance.required_with' => 'The distance field is required when location is present.',
            'country.required_without' => 'The country field is required when location is not present.',
        ];
    }
}
