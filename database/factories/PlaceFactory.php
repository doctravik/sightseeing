<?php

use App\Place;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Phaza\LaravelPostgis\Geometries\Point;

$factory->define(Place::class, function (Faker $faker) {
    $latitude = $faker->latitude;
    $longitude = $faker->longitude;

    return [
        'name' => $faker->unique()->sentence($nbWords = 3),
        'address' => $faker->address,
        'country' => $faker->country,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'user_id' => null,
        'description' => null,
        'location' => (new Point($latitude, $longitude))->toWKT(),
        'updated_at' => Carbon::now()
    ];
});
