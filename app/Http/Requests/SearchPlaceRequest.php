<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchPlaceRequest extends FormRequest
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
            'name' => 'string',
            'longitude' => 'required_with:latitude,distance|numeric',
            'latitude' => 'required_with:longitude,distance|numeric',
            'distance' => 'required_with:longitude|required_if:sort,distance|numeric',
            'country' => 'country',
            'sort' => 'sometimes|in:favorites,name,country,distance,popular',
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
            'distance.required_with' => 'The radius is required when address is present.',
            'distance.required_if' => 'The radius is required when sorting by distance'
        ];
    }
}
