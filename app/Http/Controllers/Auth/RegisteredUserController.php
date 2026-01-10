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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $encryptedEmail = \App\Casts\DeterministicEncrypted::encryptForSearch($request->email);

        if (User::where('email', $encryptedEmail)->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => __('The email has already been taken.'),
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => false, // Require admin approval
        ]);

        event(new Registered($user));

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            \Illuminate\Support\Facades\Mail::to($admins)->send(new \App\Mail\NewUserRegistered($user));
        }

        \App\Models\ActivityLog::create([
            'user_id' => $user->id, // Log as the new user themselves or system? Usually system (1)
            'action' => 'REGISTRATION',
            'description' => 'New account registered: ' . $user->name . ' (' . $user->role . ')',
        ]);

        // Don't auto-login
        // Auth::login($user);

        return redirect()->route('waiting-approval');
    }
}
