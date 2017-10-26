<?php

namespace Tests\Feature\Places;

use App\Place;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeletePlaceTest extends TestCase
{
    use DatabaseTransactions;

    public function testGuestCannotDeletePlace()
    {
        $place = $this->createPlace();

        $response = $this->json('delete', "/api/places/{$place->slug}");

        $response->assertStatus(401);
    }

    public function testAuthorCannotDeleteNotOwnPlace()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        $response = $this->actingAs($user)->json('delete', "/api/places/{$place->slug}");

        $response->assertStatus(403);
    }

    public function testAuthorWithNotConfirmedEmailCannotDeleteOwnPlace()
    {
        $author = $this->createUser(['confirmed' => false]);
        $place = $this->createPlace(['user_id' => $author->id]);

        $response = $this->actingAs($author)->json('delete', "/api/places/{$place->slug}");

        $response->assertStatus(403);
    }

    public function testAuthorCanDeleteOwnPlace()
    {
        [$author, $place] = $this->createPlaceWithAuthor();

        $response = $this->actingAs($author)->json('delete', "/api/places/{$place->slug}");

        $response->assertStatus(200);
        $this->assertCount(0, Place::all());
    }

    public function testImageFileIsDeletedWhenPlaceIsDeleted()
    {
        [$author, $place] = $this->createPlaceWithAuthor([
            'image' => $this->createPhoto()
        ]);

        $this->assertCount(1, $this->storage->allFiles());

        $response = $this->actingAs($author)->json('delete', "/api/places/{$place->slug}");

        $response->assertStatus(200);
        $this->assertCount(0, Place::all());
        $this->assertCount(0, $this->storage->allFiles());
    }
}
