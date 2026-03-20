<x-guest-layout>
    <div class="muted" style="margin-bottom: 1rem;">
        {{ __('Dit is een beveiligd gedeelte. Bevestig je wachtwoord om verder te gaan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Wachtwoord')" />

            <x-text-input id="password"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div style="margin-top: 1rem;">
            <x-primary-button>
                {{ __('Bevestigen') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
