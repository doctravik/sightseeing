<?php

namespace App\Demo;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Demo
{
    /**
     * Name of demo account.
     *
     * @var string
     */
    protected $name;

    /**
     * Email of the demo account.
     *
     * @var string
     */
    protected $email;

    /**
     * Password of the demo account.
     *
     * @var string
     */
    protected $password;

    /**
     * Create a new instance of Demo.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function __construct($name = 'Demo', $email = 'demo@example.com', $password = 'secret')
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Login as Demo user.
     *
     * @return boolean
     */
    public static function login()
    {
        $demo = new Demo();

        return Auth::attempt(['email' => $demo->email, 'password' => $demo->password]);
    }

    /**
     * Reset Demo account.
     *
     * @return void
     */
    public static function reset()
    {
        $demo = new Demo();

        $demo->delete();

        $demo->create();
    }

    /**
     * Delete demo account.
     *
     * @return void
     */
    protected function delete()
    {
        optional($this->user())->delete();
    }

    /**
     * Get User model of the Demo account.
     *
     * @return User
     */
    protected function user()
    {
        return User::whereEmail($this->email)->first();
    }

    /**
     * Create demo account.
     *
     * @return void
     */
    protected function create()
    {
        $user = factory(User::class)->create([
            'name' => $this->name,
            'email' => $this->email
        ]);

        $places = $user->addPlaces($this->importDefaultPlaces());

        $this->copyDefaultPhotos($places);
    }

    /**
     * Load default photos from file.
     *
     * @return array
     */
    protected function importDefaultPlaces()
    {
        return json_decode(File::get("database/seeds/data/places.json"), true);
    }

    /**
     * Copy default photos of the demo places from 'app/images' to 'images'
     *
     * @param  \Illuminate\Database\Eloquent\Collection $places
     * @return void
     */
    protected function copyDefaultPhotos($places)
    {
        $places->map(function ($place) {
            Storage::copy("app/{$place->image}", "{$place->image}");
        });
    }
}
