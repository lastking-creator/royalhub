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
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'phone_number' => ['required', 'string', 'max:20'],
        'date_of_birth' => ['required', 'date'],
        'id_number' => ['required', 'string', 'max:50', 'unique:'.User::class],
        'physical_address' => ['required', 'string', 'max:255'],
        'whatsapp_number' => ['nullable', 'string', 'max:20'],
        'next_of_kin_name' => ['required', 'string', 'max:255'],
        'next_of_kin_relationship' => ['required', 'string', 'max:100'],
        'next_of_kin_phone' => ['required', 'string', 'max:20'],
        'occupation' => ['nullable', 'string', 'max:255'],
        'talents_skills' => ['nullable', 'string'],
        'terms_accepted' => ['accepted'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone_number' => $request->phone_number,
        'date_of_birth' => $request->date_of_birth,
        'id_number' => $request->id_number,
        'physical_address' => $request->physical_address,
        'whatsapp_number' => $request->whatsapp_number,
        'next_of_kin_name' => $request->next_of_kin_name,
        'next_of_kin_relationship' => $request->next_of_kin_relationship,
        'next_of_kin_phone' => $request->next_of_kin_phone,
        'occupation' => $request->occupation,
        'talents_skills' => $request->talents_skills,
        'terms_accepted' => $request->boolean('terms_accepted'),
        'role' => 'member',
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}
