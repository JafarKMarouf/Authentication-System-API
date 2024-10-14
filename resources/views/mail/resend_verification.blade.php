<x-mail::message>
    Hello {{ $username }}!


    Your Verification code is {{ $otp }}

    Enter this code in our {{ config('app.name') }}
    to activate your account.

    This code is valid for 3 minutes.

    Thanks
    {{ config('app.name') }}

</x-mail::message>
