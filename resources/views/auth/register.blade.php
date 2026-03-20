<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" class="stack">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Naam')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('E-mailadres')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Telefoonnummer')" />

            <div style="display:flex; gap:0.5rem; align-items:center;">
                
                <select 
                    id="country_code" 
                    name="country_code"
                    style="
                        width: 130px;
                        padding: 0.5rem;
                        border-radius: 6px;
                        border: 1px solid #ccc;
                        background: white;
                    "
                >
                    <option value="+31" selected>+31 🇳🇱</option>
                    <option value="+32">+32 🇧🇪</option>
                    <option value="+49">+49 🇩🇪</option>
                    <option value="+33">+33 🇫🇷</option>
                    <option value="+44">+44 🇬🇧</option>
                    <option value="+34">+34 🇪🇸</option>
                    <option value="+39">+39 🇮🇹</option>
                    <option value="+1">+1 🇺🇸</option>
                </select>

                <x-text-input 
                    id="phone"
                    name="phone"
                    type="tel"
                    placeholder="0612345678"
                    required
                    style="flex:1;"
                />
            </div>

            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Wachtwoord')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Bevestig wachtwoord')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <!-- Submit & Login Link -->
        <div class="stack" style="flex-direction: row; gap: 0.75rem;">
            <x-primary-button>
                {{ __('Registreren') }}
            </x-primary-button>

            <a href="{{ route('login') }}" class="muted" style="text-decoration: underline;">
                {{ __('Al geregistreerd?') }}
            </a>
        </div>

    </form>
</x-guest-layout>
