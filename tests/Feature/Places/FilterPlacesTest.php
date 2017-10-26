<?php

namespace Tests\Feature\Places;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FilterPlacesTest extends TestCase
{
    use DatabaseTransactions;

    public function testAuthenticatedUserCanGetOwnPlaces()
    {
        [$user, $place] = $this->createPlaceWithAuthor();
        $anotherPlace = $this->createPlace();

        $response = $this->actingAs($user)->json('get', '/api/places?my');

        $response->assertStatus(200);
        $this->assertCount(1, $places = $response->json()['data']);
        $response->assertJsonFragment(["id" => $place->id]);
        $response->assertJsonMissing(["id" => $anotherPlace->id]);
    }

    public function testGuestGetAllExistingPlacesWhenFiltersByMy()
    {
        $placeOne = $this->createPlace();
        $placeTwo = $this->createPlace();

        $response = $this->json('get', '/api/places?my');

        $response->assertStatus(200);
        $this->assertCount(2, $places = $response->json()['data']);
        $response->assertJsonFragment(['id' => $placeOne->id, 'id' => $placeTwo->id]);
    }

    public function testGuestCanFilterPlaceByAuthor()
    {
        [$user, $place] = $this->createPlaceWithAuthor();
        $anotherPlace = $this->createPlace();

        $response = $this->json('get', "/api/places?author=$user->slug");

        $response->assertStatus(200);
        $this->assertCount(1, $places = $response->json()['data']);
        $response->assertJsonFragment(["id" => $place->id]);
        $response->assertJsonMissing(["id" => $anotherPlace->id]);
    }

    public function testItReturnsAllPlacesWhenFilterByAuthorAndAuthorNotExist()
    {
        $user = $this->createUser(['email' => 'john@example.com']);
        $place = $this->createPlace(['user_id' => $user->id]);

        $response = $this->json('get', "/api/places?author=not_existing_author_slug");

        $response->assertStatus(200);
        $this->assertCount(1, $places = $response->json()['data']);
        $response->assertJsonFragment(["id" => $place->id]);
    }
}
