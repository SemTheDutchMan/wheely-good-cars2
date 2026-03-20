<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="auth-layout">

        <!-- Logo -->
        <div>
            <a href="/">
                <x-application-logo />
            </a>
        </div>

        <!-- Back Button -->
        <div class="auth-back">
            <a href="{{ route('home') }}" class="btn btn-outline">
                &larr; Terug
            </a>
        </div>

        <!-- Form / Page Content Slot -->
        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
