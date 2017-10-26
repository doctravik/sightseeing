<?php

namespace Tests\Unit;

use App\User;
use App\Place;
use Tests\TestCase;
use Phaza\LaravelPostgis\Geometries\Point;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NearestPlacesTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanFindNearestPlacesWhenModelGiven()
    {
        [$kiev, $dnipro, $zaporizhzhia] = $this->createPlaces();

        $places = Place::nearestTo($kiev)->within(400)->find()->pluck('id');

        $this->assertCount(2, $places);
        $this->assertTrue($places->contains($kiev->id));
        $this->assertTrue($places->contains($dnipro->id));
    }

    public function testItCanFindNearestPlacesWhenAssociativeArrayIsGiven()
    {
        [$kiev, $dnipro, $zaporizhzhia] = $this->createPlaces();

        $places = Place::nearestTo([
                'latitude' => $kiev->latitude,
                'longitude' => $kiev->longitude
            ])->within(400)
            ->find()
            ->pluck('id');

        $this->assertCount(2, $places);
        $this->assertTrue($places->contains($kiev->id));
        $this->assertTrue($places->contains($dnipro->id));
    }

    public function testItCanFindNearestPlacesWhenSimpleArrayIsGiven()
    {
        [$kiev, $dnipro, $zaporizhzhia] = $this->createPlaces();

        $places = Place::nearestTo([$kiev->latitude, $kiev->longitude])
            ->within(400)
            ->find()
            ->pluck('id');

        $this->assertCount(2, $places);
        $this->assertTrue($places->contains($kiev->id));
        $this->assertTrue($places->contains($dnipro->id));
    }

    public function testItCanFindNearestPlacesWhenTwoArgumentsAreGiven()
    {
        [$kiev, $dnipro, $zaporizhzhia] = $this->createPlaces();

        $places = Place::nearestTo($kiev->latitude, $kiev->longitude)
            ->within(400)
            ->find()
            ->pluck('id');

        $this->assertCount(2, $places);
        $this->assertTrue($places->contains($kiev->id));
        $this->assertTrue($places->contains($dnipro->id));
    }

    public function testItThrowsExceptionIfArgumentIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $places = Place::nearestTo(
            factory(User::class)->make()
        )->find();
    }

    protected function createPlaces()
    {
        // zero point
        $kiev = factory(Place::class)->create([
            'latitude' => 50.45010000,
            'longitude' => 30.52340000,
            'location' => new Point(50.45010000, 30.52340000)
        ]);

        // distance between Kiev and Dnipro - 394 km
        $dnipro = factory(Place::class)->create([
            'location' => new Point(48.46471700, 35.04618300)
        ]);

        // distance between Kiev and Zaporizhzhia - 444 km
        $zaporizhzhia = factory(Place::class)->create([
            'location' => new Point(47.83880000, 35.13956700)
        ]);

        return [$kiev, $dnipro, $zaporizhzhia];
    }
}
