@component('mail::message')
{{-- Greeting --}}
# {{ 'Hello ' . $user->name }},

{{-- Intro Lines --}}
You have received the link to disable your 2FA authentication into the system.

Click below to disable 2FA authentication.

{{-- Action Button --}}
@component('mail::button', ['url' => route('reset-2fa', $resetToken), 'color' => 'blue'])
Disable 2FA
@endcomponent

Regards,<br>Next Exchange

{{-- Subcopy --}}
{{--@component('mail::subcopy')
If you're having trouble clicking the "Login" button, copy and paste the URL below
into your web browser: [ {{route('login')}} ]({{route('login')}})
@endcomponent--}}

@endcomponent