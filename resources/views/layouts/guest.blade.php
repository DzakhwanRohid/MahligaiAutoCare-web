<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mahligai Auto Care') }}</title>

        <link rel="icon" href="{{ asset('img/logo_project.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

</head>
<body class="auth-page">

    <div id="particles-js"></div>

    <div class="auth-content-wrapper">
        <div class="auth-container">
            <div>
                <a href="/">
                    <x-application-logo class="auth-logo" />
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>

    <script src="{{ asset('js/particles.js') }}"></script>

    <script src="{{ asset('js/auth-particles.js') }}"></script>

</body>
</html>
