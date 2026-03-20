<x-guest-layout>
    <div class="muted" style="margin-bottom: 1rem;">
        {{ __('Bedankt voor je registratie! Bevestig je e-mailadres via de link die we zojuist hebben gestuurd. Geen mail ontvangen? Vraag hieronder een nieuwe aan.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="status" style="margin-bottom: 1rem;">
            {{ __('Er is een nieuwe verificatielink gestuurd naar het e-mailadres waarmee je je hebt geregistreerd.') }}
        </div>
    @endif

    <div class="stack" style="flex-direction: row; justify-content: space-between; align-items: center;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Verificatiemail opnieuw sturen') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-outline">
                {{ __('Uitloggen') }}
            </button>
        </form>
    </div>
</x-guest-layout>
