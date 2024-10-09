<x-mail::message>
    Resetting Password

    use the below code for reset password

    Your OTP code is {{ $token }}

    This code is valid for 10 minutes.

    Thanks

    {{ config('app.name') }}

</x-mail::message>
