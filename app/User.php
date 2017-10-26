<?php

namespace App;

use App\Favorites\HasFavorites;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasFavorites;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'confirmation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean'
    ];

    /**
     * Check if the user is an admin.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Confirm email address.
     *
     * @return void
     */
    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;

        $this->save();
    }

    /**
     * Send to the user confirmation email with token.
     *
     * @return void
     */
    public function getConfirmationEmail()
    {
        Mail::to($this)->send(new PleaseConfirmYourEmail($this));
    }

    /**
     * Set user's email attribute.
     *
     * @param string $email
     * @param void
     */
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = $email;
        $this->attributes['slug'] = str_slug($email);
    }

    /**
     * User can has create many places.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
