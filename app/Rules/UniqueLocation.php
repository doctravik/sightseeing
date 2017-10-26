<?php

namespace App\Rules;

use App\Place;
use Illuminate\Contracts\Validation\Rule;

class UniqueLocation implements Rule
{
    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Place::whereRaw("location = ST_MakePoint(?, ?)", [$this->longitude, $this->latitude])
            ->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The place with such coordinates has already been taken.';
    }
}
