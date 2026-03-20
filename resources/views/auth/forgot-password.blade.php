<x-guest-layout>
    <div class="muted" style="margin-bottom: 1rem;">
        {{ __('Wachtwoord vergeten? Geen probleem. Vul je e-mailadres in en we sturen je een link om je wachtwoord te resetten.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('E-mailadres')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div style="margin-top: 1rem;">
            <x-primary-button>
                {{ __('Stuur resetlink') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
