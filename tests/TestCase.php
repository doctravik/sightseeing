<?php

namespace Tests;

use App\User;
use App\Photo;
use App\Place;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Fake storage.
     *
     * @var Illuminate\Filesystem\FilesystemAdapter
     */
    protected $storage;

    public function setUp()
    {
        parent::setUp();

        $this->storage = $this->fakeStorage();
    }

    /**
     * Set fake previous url.
     *
     * @param  string $url
     * @return TestCase
     */
    protected function from($url)
    {
        $url = env('APP_URL') . $url;
        $this->app['session']->setPreviousUrl($url);

        return $this;
    }

    /**
     * Create fake storage.
     *
     * @param string $name
     * @return Illuminate\Filesystem\FilesystemAdapter
     */
    protected function fakeStorage($name = 'album')
    {
        Storage::fake($name);
        config(['filesystems.default' => $name]);

        return Storage::disk($name);
    }

    /**
     * Create new Place.
     *
     * @param  array $attributes
     * @return Place
     */
    protected function createPlace($attributes = [])
    {
        return factory(Place::class)->create($attributes);
    }

    /**
     * Create new User.
     *
     * @param  array $attributes
     * @return User
     */
    protected function createUser($attributes = [])
    {
        return factory(User::class)->create($attributes);
    }

    /**
     * Create new Place with it's author.
     *
     * @param  array $attributes of the Place
     * @return array
     */
    protected function createPlaceWithAuthor($attributes = [])
    {
        $user = $this->createUser();

        $place = $this->createPlace(
            array_merge($attributes, ['user_id' => $user->id])
        );

        return [$user, $place];
    }

    /**
     * Upload image.
     *
     * @return string path
     */
    protected function createPhoto()
    {
        return Photo::create(UploadedFile::fake()->image('avatar.jpg', 900, 600))->save();
    }
}
