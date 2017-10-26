@component('mail::message')
# One last step

Please confirm your email address.

@component('mail::button', ['url' => route('confirm.email', ['token' => $user->confirmation_token]) ])
Confirm email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
