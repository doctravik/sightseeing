<?php

namespace Tests\Feature\Places;

use App\Place;
use Tests\TestCase;
use Phaza\LaravelPostgis\Geometries\Point;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchNearestPlacesTest extends TestCase
{
    use DatabaseTransactions;

    public function testItCanFindNearestPlaces()
    {
        [$kiev, $dnipro, $zaporizhzhia] = $this->createPlaces();

        $response = $this->json('get', '/api/geo-search', [
            'latitude' => $kiev->latitude,
            'longitude' => $kiev->longitude,
            'distance' => 400
        ]);

        $response->assertStatus(200);
        $this->assertCount(2, $places = $response->json()['data']);
        $response->assertJsonFragment(["id" => $kiev->id]);
        $response->assertJsonFragment(["id" => $dnipro->id]);
        $response->assertJsonMissing(["id" => $zaporizhzhia->id]);
    }

    public function testItCanFindNearestPlacesByCountry()
    {
        [$kiev, $dnipro, $zaporizhzhia] = $this->createPlaces();
        $london = factory(Place::class)->create(['country' => 'United Kingdom']);

        $response = $this->json('get', '/api/geo-search', [
            'country' => 'Ukraine'
        ]);

        $response->assertStatus(200);
        $this->assertCount(3, $places = $response->json()['data']);
        $response->assertJsonFragment(["id" => $kiev->id]);
        $response->assertJsonFragment(["id" => $dnipro->id]);
        $response->assertJsonFragment(["id" => $zaporizhzhia->id]);
        $response->assertJsonMissing(["id" => $london->id]);
    }

    public function testItRequiredDistanceWhenLongitudeIsPresent()
    {
        [$kiev, $dnipro, $zaporizhzhia] = $this->createPlaces();

        $response = $this->json('get', '/api/geo-search', [
            'latitude' => $kiev->latitude,
            'longitude' => $kiev->longitude,
        ]);

        $response->assertStatus(422);
        $this->assertCount(1, $response->json()['errors']);
        $this->assertArrayHasKey('distance', $response->json()['errors']);
    }

    public function testItRequiredLatitudeAndLongitudeWhenDistanceIsPresent()
    {
        [$kiev, $dnipro, $zaporizhzhia] = $this->createPlaces();

        $response = $this->json('get', '/api/geo-search', [
            'distance' => 400,
        ]);

        $response->assertStatus(422);
        $this->assertCount(3, $response->json()['errors']);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
        $this->assertArrayHasKey('latitude', $response->json()['errors']);
        $this->assertArrayHasKey('country', $response->json()['errors']);
    }

    public function testItRequiredCountryWhenRequestBodyIsEmpty()
    {
        $response = $this->json('get', '/api/geo-search');

        $response->assertStatus(422);
        $this->assertCount(1, $response->json()['errors']);
        $this->assertArrayHasKey('country', $response->json()['errors']);
    }

    public function testItNotValidateRequestWithEmptyValues()
    {
        $response = $this->json('get', '/api/geo-search', [
            'latitude' => '',
            'longitude' => '',
            'distance' => '',
            'country' => ''
        ]);

        $response->assertStatus(422);
        $this->assertCount(4, $response->json()['errors']);
        $this->assertArrayHasKey('latitude', $response->json()['errors']);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
        $this->assertArrayHasKey('distance', $response->json()['errors']);
        $this->assertArrayHasKey('country', $response->json()['errors']);
    }

    public function testItNotValidateRequestWithIfValuesAreNotNumeric()
    {
        $response = $this->json('get', '/api/geo-search', [
            'latitude' => 'hello',
            'longitude' => true,
            'distance' => 'text'
        ]);

        $response->assertStatus(422);
        $this->assertCount(3, $response->json()['errors']);
        $this->assertArrayHasKey('latitude', $response->json()['errors']);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
        $this->assertArrayHasKey('distance', $response->json()['errors']);
    }

    public function testItNotValidateRequestWithNotValidCountry()
    {
        $response = $this->json('get', '/api/geo-search', [
            'country' => 'text'
        ]);

        $response->assertStatus(422);
        $this->assertCount(1, $response->json()['errors']);
        $this->assertArrayHasKey('country', $response->json()['errors']);
    }

    protected function createPlaces()
    {
        // zero point
        $kiev = factory(Place::class)->create([
            'latitude' => 50.45010000,
            'longitude' => 30.52340000,
            'location' => new Point(50.45010000, 30.52340000),
            'country' => 'Ukraine'
        ]);

        // distance between Kiev and Dnipro - 394 km
        $dnipro = factory(Place::class)->create([
            'location' => new Point(48.46471700, 35.04618300),
            'country' => 'Ukraine'
        ]);

        // distance between Kiev and Zaporizhzhia - 444 km
        $zaporizhzhia = factory(Place::class)->create([
            'location' => new Point(47.83880000, 35.13956700),
            'country' => 'Ukraine'
        ]);

        return [$kiev, $dnipro, $zaporizhzhia];
    }
}
