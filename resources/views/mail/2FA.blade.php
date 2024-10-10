<x-mail::message>

    Hello {{ $username }},

    This is your 2FA login verification code: {{ $token }}

    This code is valid for 3 minutes.

    Stay safe!

    {{ config('app.name') }}

</x-mail::message>
