<x-mail::message>
    <h1>Hello, {{ $username }}!</h1>
    <p>Your Verification code is: <strong>{{ $otp }}</strong> </p>
    <p>
        Enter this code in our
        <strong>{{ config('app.name') }}</strong>
        to activate your account.
    </p>
    <p>This code is valid for <strong>3 minutes</strong>.</p>
    <p>Thanks</p>
    <footer>
        {{ config('app.name') }} &copy; {{ date('Y') }}
    </footer>
</x-mail::message>
