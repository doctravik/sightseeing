<?php

namespace App\Http\Requests;

use App\Place;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlaceRequest extends FormRequest
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
            'name' => ['required', 'string', Rule::unique('places')->ignore($this->place->id) ],
            'country' => 'required|string',
            'description' => 'sometimes|string|nullable',
            'latitude' => 'required|numeric',
            'longitude' => [
                        'required',
                        'numeric',
                        Rule::unique('places')->where('latitude', $this->latitude)
                            ->ignore($this->place->id)
                    ]
        ];
    }

    /**
     * Get the custom error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'longitude.unique' => 'The place with such coordinates has already been taken.'
        ];
    }
}
