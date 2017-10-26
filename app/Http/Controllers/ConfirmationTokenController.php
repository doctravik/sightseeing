<?php

namespace App\Http\Controllers;

use App\User;
use App\Flash\Message;

class ConfirmationTokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('resend');
    }

    /**
     * Confirm email address.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm()
    {
        $user = User::where('confirmation_token', request('token'))->first();

        if (!$user) {
            return redirect('/places')
                ->with('flash', new Message('Your email was not confirmed because of invalid token.', 'danger'));
        }

        $user->confirm();

        return redirect('/places')
            ->with('flash', new Message('Your email was successfully confirmed.', 'success'));
    }

    /**
     * Resend confirmation token on user's request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend()
    {
        $user = auth()->user();

        if ($user->confirmed) {
            return redirect(url()->previous())
                ->with(['flash' => new Message('Your email address have been already confirmed', 'success')]);
        }

        $user->getConfirmationEmail();

        return redirect(url()->previous())
            ->with(['flash' => new Message('Confirmation token was sent to your email', 'success')]);
    }
}
