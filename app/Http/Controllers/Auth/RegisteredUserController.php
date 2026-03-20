<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
        {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            'country_code' => ['nullable', 'string', 'max:6'],
            'phone' => ['nullable', 'string', 'max:25'],
        ]);

        $fullPhone = null;

        if ($request->filled('phone')) {
            $countryCode = $request->input('country_code', '+31');

            // alleen cijfers uit phone halen
            $phone = preg_replace('/[^0-9]/', '', (string) $request->phone);

            // voorloopnul verwijderen (06 -> 6), werkt goed voor NL en meestal ook voor andere landen
            $phone = ltrim($phone, '0');

            $fullPhone = $countryCode . $phone;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $fullPhone,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
        }
}
