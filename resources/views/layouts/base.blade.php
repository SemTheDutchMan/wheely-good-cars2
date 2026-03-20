<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WheelyGoodCars</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="{{ trim($__env->yieldContent('body_class')) }}">
    <header class="site-header">
        <div class="shell topbar">
            <div class="topbar-left">
                <a href="{{ route('home') }}" class="brand">Wheely good cars!</a>

                <nav class="topnav">
                    <a href="{{ route('home') }}">Alle auto's</a>
                    @auth
                        <a href="{{ route('cars.mycars') }}">Mijn aanbod</a>
                        <a href="{{ route('offercar') }}">Aanbod plaatsen</a>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}">Beheer</a>
                            <a href="{{ route('admin.live-dashboard') }}">Dashboard</a>
                        @endif
                    @endauth
                </nav>
            </div>

            <div class="topbar-actions">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-logout">Uitloggen</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-logout">Log in</a>
                    <a href="{{ route('register') }}" class="nav-logout">Registreer</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="shell page-shell">
        @if (session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="flash flash-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash flash-error">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
