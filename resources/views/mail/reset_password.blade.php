<x-mail::message>

    Hello {{ $username }},

    Please reset your password using the below code

    Your OTP code is {{ $token }}

    This code is valid for 10 minutes.

    Thanks

    {{ config('app.name') }}

</x-mail::message>
