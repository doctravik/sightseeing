<?php

namespace Tests\Feature\Users;

use App\User;
use Tests\TestCase;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function testItSendsConfirmationEmailWhenUserRegistered()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response->assertStatus(302);

        Mail::assertSent(PleaseConfirmYourEmail::class, function ($mail) {
            return $mail->hasTo('johndoe@example.com');
        });
    }

    public function testUserCanConfirmEmail()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $user = User::where('email', 'johndoe@example.com')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $response = $this->get(route('confirm.email', ['token' => $user->confirmation_token]));

        $response->assertRedirect('/places');

        tap($user = $user->fresh(), function ($u) {
            $this->assertTrue($u->confirmed);
            $this->assertNull($u->confirmation_token);
        });
    }

    public function testUserCanRequestConfirmationToken()
    {
        Mail::fake();

        $user = factory(User::class)->states('unconfirmed')->create();

        $response = $this->actingAs($user)->post(route('send.confirmation.token'));

        $response->assertStatus(302);

        Mail::assertSent(PleaseConfirmYourEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user);
        });
    }

    public function testConfirmedUserDoesNotGetConfirmationEmail()
    {
        Mail::fake();

        $user = $this->createUser();

        $response = $this->actingAs($user)->post(route('send.confirmation.token'));

        $response->assertStatus(302);

        Mail::assertNotSent(PleaseConfirmYourEmail::class);
    }

    public function testUnauthenticatedUserCannotRequestConfirmationToken()
    {
        Mail::fake();

        $response = $this->post(route('send.confirmation.token'));

        $response->assertRedirect(route('login'));
    }
}
