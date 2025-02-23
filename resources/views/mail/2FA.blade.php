<x-mail::message>
    <h1>Hello, {{ $username }}!</h1>
    <p>This is your 2FA login verification code: <strong>{{ $token }}</strong></p>
    <p>This code is valid for <strong>3 minutes</strong>.</p>
    <p>Stay safe!</p>
    <footer>
        {{ config('app.name') }} &copy; {{ date('Y') }}
    </footer>
</x-mail::message>
