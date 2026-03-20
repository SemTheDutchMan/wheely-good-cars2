<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="stack">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('E-mailadres')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Wachtwoord')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div>
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me" class="muted">Onthoud mij</label>
        </div>

        <!-- Submit Button -->
        <div class="stack" style="flex-direction: row; gap: 0.75rem;">
            <x-primary-button>
                {{ __('Inloggen') }}
            </x-primary-button>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="muted" style="text-decoration: underline;">
                    {{ __('Wachtwoord vergeten?') }}
                </a>
            @endif
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="muted" style="text-decoration: underline;">
                    {{ __('Nog geen account? Registreren') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
