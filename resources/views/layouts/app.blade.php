<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'WheelyGoodCars') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <header class="header">
        <div class="header-container">
            <h1 class="logo">WheelyGoodCars</h1>

            <nav class="nav">
                <a href="{{ route('home') }}" class="nav-link">Alle auto's</a>
                <a href="{{ route('cars.mycars') }}" class="nav-link">Mijn aangeboden auto's</a>
                <a href="{{ route('offercar') }}" class="nav-link">Aanbod plaatsen</a>
            </nav>

            <div class="auth-links">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Log uit</button>
                    </form>
                    <p class="muted">Ingelogd als: {{ Auth::user()->name }}</p>
                @else
                    <a href="{{ route('login') }}">Log in</a>
                    <a href="{{ route('register') }}">Registreer</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="main-content">
        @if (isset($header))
            <div class="container page">
                <div class="card">
                    {{ $header }}
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="footer">
        <div class="footer-container">
            © {{ date('Y') }} WheelyGoodCars. All rights reserved.
        </div>
    </footer>

</body>
</html>
