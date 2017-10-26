<?php

namespace Tests\Feature\Places;

use App\User;
use App\Place;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FavoritePlaceTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserCanAddPlaceToTheFavorites()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        $this->assertUserFavoritesMissing($user, $place);

        $response = $this->actingAs($user)->json('post', "/api/places/{$place->slug}/favorites");

        $response->assertStatus(201);

        $this->assertUserFavoritesHas($user->fresh(), $place);
    }

    public function testUserCanRemovePlaceFromFavorites()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        $user->like($place);

        $this->assertUserFavoritesHas($user->fresh(), $place);

        $response = $this->actingAs($user)->json('delete', "/api/places/{$place->slug}/favorites");

        $response->assertStatus(200);

        $this->assertUserFavoritesMissing($user->fresh(), $place);
    }

    public function testUserCannotAddTheSamePlaceToTheFavoritesTwice()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        $this->assertUserFavoritesMissing($user, $place);

        $response = $this->actingAs($user)->json('post', "/api/places/{$place->slug}/favorites");

        $response->assertStatus(201);
        $this->assertCount(1, $user->fresh()->favorites);

        $response = $this->actingAs($user)->json('post', "/api/places/{$place->slug}/favorites");

        $response->assertStatus(200);
        $this->assertCount(1, $user->fresh()->favorites);
    }

    public function testGuestCannotLikedThePlace()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        $response = $this->json('post', "/api/places/{$place->slug}/favorites");

        $response->assertStatus(401);
    }

    public function testUnconfirmedUserCannotLikedThePlace()
    {
        $user = $this->createUser(['confirmed' => false]);
        $place = $this->createPlace();

        $response = $this->actingAs($user)->json('post', "/api/places/{$place->slug}/favorites");

        $response->assertStatus(403);
    }

    /**
     * Assert that user has the given place in his favorite's list.
     *
     * @param  User $user
     * @param  Place $place
     * @return void
     */
    protected function assertUserFavoritesHas(User $user, Place $place)
    {
        $this->assertCount(1, $user->favorites);
        $this->assertTrue($place->isLikedBy($user));
    }

    /**
     * Assert that user has not the given place in his favorite's list.
     *
     * @param  User $user
     * @param  Place $place
     * @return void
     */
    protected function assertUserFavoritesMissing(User $user, Place $place)
    {
        $this->assertCount(0, $user->favorites);
        $this->assertFalse($place->isLikedBy($user));
    }
}
