<?php

namespace Tests\Feature\Places;

use App\User;
use App\Place;
use Tests\TestCase;
use Phaza\LaravelPostgis\Geometries\Point;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdatePlaceTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->place = $this->place();

        $this->endpoint = "api/places/{$this->place->slug}";
    }

    public function testGuestCannotUpdatePlace()
    {
        $response = $this->json('patch', $this->endpoint, $this->place->toArray());

        $response->assertStatus(401);
    }

    public function testUserCannotUpdateNotOwnPlace()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->json('patch', $this->endpoint, $this->place->toArray());

        $response->assertStatus(403);
    }

    public function testUnconfirmedUserCannotUpdateOwnPlace()
    {
        $user = factory(User::class)->states('unconfirmed')->create();
        $place = factory(Place::class)->create(['name' => 'Paris', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->json(
            'patch',
            "/api/places/{$place->slug}",
            array_merge($place->toArray(), ['name' => 'Kiev'])
        );

        $response->assertStatus(403);
    }

    public function testAuthorCanUpdateOwnPlace()
    {
        $user = factory(User::class)->create();
        $place = factory(Place::class)->create(['name' => 'Paris', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->json(
            'patch',
            "/api/places/{$place->slug}",
            array_merge($place->toArray(), ['name' => 'Kiev'])
        );

        $response->assertStatus(200);
        $this->assertEquals('Kiev', $place->fresh()->name);
    }

    public function testItCannotUpdatePlaceWithTheSameNameAndDifferentId()
    {
        $user = $this->createUser();
        $kiev = $this->createPlace(['name' => 'Kiev', 'user_id' => $user->id ]);
        $paris = $this->createPlace(['name' => 'Paris', 'user_id' => $user->id ]);

        $response = $this->actingAs($user)->json(
            'patch',
            "/api/places/{$paris->slug}",
            array_merge($paris->toArray(), ['name' => 'Kiev'])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $errors = $response->json()['errors']);
        $this->assertCount(1, $errors);
    }

    public function testItCanUpdatePlaceWithTheSameNameAndSameId()
    {
        $user = $this->createUser();
        $place = $this->createPlace(['name' => 'Kiev', 'user_id' => $user->id ]);

        $response = $this->actingAs($user)->json(
            'patch',
            "/api/places/{$place->slug}",
            array_merge($place->toArray(), ['name' => 'Kiev'])
        );

        $response->assertStatus(200);
    }

    public function testItCannotUpdatePlaceWithTheSameCoordinatesAndDifferentId()
    {
        $user = $this->createUser();
        $placeOne = $this->createPlace(['user_id' => $user->id]);
        $placeTwo = $this->createPlace(['user_id' => $user->id]);

        $response = $this->actingAs($user)->json(
            'patch',
            "/api/places/{$placeOne->slug}",
            array_merge($placeOne->toArray(), [
                'latitude' => $placeTwo->latitude,
                'longitude' => $placeTwo->longitude
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
    }

    public function testItCanUpdatePlaceWithTheSameCoordinatesAndSameId()
    {
        $user = $this->createUser();
        $place = $this->createPlace(['user_id' => $user->id]);

        $response = $this->actingAs($user)->json(
            'patch',
            "/api/places/{$place->slug}",
            array_merge($place->toArray(), ['name' => 'Kiev'])
        );

        $response->assertStatus(200);
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

        return factory(Place::class)->create(array_merge($attributes, $override));
    }
}
