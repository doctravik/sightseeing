<?php

namespace Tests\Feature\Places;

use App\Place;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Phaza\LaravelPostgis\Geometries\Point;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePlaceTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreatePlace1()
    {
        $response = $this->json('post', '/api/places', $this->place()->toArray());

        $response->assertStatus(201);
        $this->assertCount(1, Place::all());
    }

    public function testCreatePlaceWithImage()
    {
        $response = $this->json('post', '/api/places', $this->place([
            'image' => $file = UploadedFile::fake()->image('avatar.jpg', 900, 600)
        ])->toArray());

        $response->assertStatus(201);
        $this->assertCount(1, Place::all());
        $this->assertEquals($path = 'images/'. $file->hashName(), Place::first()->image);
        $this->assertCount(1, $this->storage->allFiles());
        $this->storage->assertExists($path);
        $this->assertEquals(200, Image::make($this->storage->get($path))->height());
        $this->assertEquals(300, Image::make($this->storage->get($path))->width());
    }

    public function testTwoPlaceWithSameCoordinatesAreNotAllowed()
    {
        factory(Place::class)->create($this->place()->toArray());

        $response = $this->json('post', '/api/places', $this->place()->toArray());

        $response->assertStatus(422);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
        $this->assertCount(1, Place::all());
    }

    public function testTwoPlaceWithSameButRoundedCoordinatesAreNotAllowed()
    {
        factory(Place::class)->create($this->place()->toArray());

        $response = $this->json('post', '/api/places', $this->place([
            'latitude' => 38.90719230,
            'longitude' => -77.03687070,
        ])->toArray());

        $response->assertStatus(422);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
        $this->assertCount(1, Place::all());
    }

    public function testPlaceWithSameNameareNotAllowed()
    {
        $place = $this->createPlace(['name' => 'Washington']);

        $response = $this->json('post', '/api/places', $this->place()->toArray());

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->json()['errors']);
        $this->assertCount(1, Place::all());
    }

    public function testPlaceSlugIsCreatedAutomatically()
    {
        $place = $this->createPlace();

        $this->assertEquals(str_slug($place->name), $place->fresh()->slug);
    }

    /**
     * Create fake place.
     *
     * @return Place
     */
    protected function place($override = [])
    {
        $attributes = [
            'name' => 'Washington',
            'address' => 'Washington, DC, USA',
            'country' => 'USA',
            'latitude' => 38.90719230,
            'longitude' => -77.03687070000001,
            'location' => new Point(38.90719230, -77.03687070000001)
        ];

        return factory(Place::class)->make(array_merge($attributes, $override));
    }
}
