<?php

namespace Tests\Feature\Places;

use App\Photo;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploadImageTest extends TestCase
{
    use DatabaseTransactions;

    public function testGuestCannotUploadImage()
    {
        $place = $this->createPlace();

        $response = $this->json('post', "/api/places/{$place->slug}/images", [
            'image' => UploadedFile::fake()->image('avatar.jpg', 900, 600)
        ]);

        $response->assertStatus(401);
        $this->assertCount(0, $this->storage->allFiles());
    }

    public function testUserCannotUploadImageForNotOwnPlace()
    {
        $user = $this->createUser();
        $place = $this->createPlace();

        $response = $this->actingAs($user)->json('post', "/api/places/{$place->slug}/images", [
            'image' => UploadedFile::fake()->image('avatar.jpg', 900, 600)
        ]);

        $response->assertStatus(403);
        $this->assertCount(0, $this->storage->allFiles());
    }

    public function testUnconfirmedAuthorCannotUploadImageForOwnPlace()
    {
        $author = $this->createUser(['confirmed' => false]);
        $place = $this->createPlace(['user_id' => $author->id]);

        $response = $this->actingAs($author)->json('post', "/api/places/{$place->slug}/images", [
            'image' => $file = UploadedFile::fake()->image('avatar.jpg', 900, 600)
        ]);

        $response->assertStatus(403);
    }

    public function testAuthorCanUploadImageForOwnPlace()
    {
        [$author, $place] = $this->createPlaceWithAuthor();

        $response = $this->actingAs($author)->json('post', "/api/places/{$place->slug}/images", [
            'image' => $file = UploadedFile::fake()->image('avatar.jpg', 900, 600)
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, $this->storage->allFiles());
        $this->assertEquals("images/{$file->hashName()}", $place->fresh()->image);
    }

    public function testOldImageFileIsDeletedWhenNewImageIsUploaded()
    {
        $oldPhoto = Photo::create(
            UploadedFile::fake()->image('avatar.jpg', 900, 600)
        )->save();

        [$author, $place] = $this->createPlaceWithAuthor(['image' => $oldPhoto]);

        $response = $this->actingAs($author)->json('post', "/api/places/{$place->slug}/images", [
            'image' => $newPhoto = UploadedFile::fake()->image('avatar.jpg', 900, 600)
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, $this->storage->allFiles());
        $this->storage->assertMissing($oldPhoto);
        $this->storage->assertExists($newPhotoPath = "images/{$newPhoto->hashName()}");
        $this->assertEquals($newPhotoPath, $place->fresh()->image);
    }

    public function testAuthorCanRemoveImageForPlace()
    {
        [$author, $place] = $this->createPlaceWithAuthor();

        $response = $this->actingAs($author)->json('post', "/api/places/{$place->slug}/images", [
            'image' => $file = UploadedFile::fake()->image('avatar.jpg', 900, 600)
        ]);

        $response->assertStatus(200);
        $this->assertCount(1, $this->storage->allFiles());
        $this->assertEquals("images/{$file->hashName()}", $place->fresh()->image);

        $response = $this->actingAs($author)->json('delete', "/api/places/{$place->slug}/images");

        $response->assertStatus(200);
        $this->assertCount(0, $this->storage->allFiles());
        $this->assertNull($place->fresh()->image);
    }

    public function testItDoesntValidateNotImageFile()
    {
        [$author, $place] = $this->createPlaceWithAuthor();

        $response = $this->actingAs($author)->json('post', "/api/places/{$place->slug}/images", [
            'image' => $file = UploadedFile::fake()->image('avatar.pdf')
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('image', $response->json()['errors']);
        $this->assertCount(0, $this->storage->allFiles());
        $this->assertNull($place->fresh()->image);
    }
}
