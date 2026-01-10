<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        // Use deterministic encryption to find matching user in encrypted DB
        $encryptedEmail = \App\Casts\DeterministicEncrypted::encryptForSearch($request->email);

        // Validate that the user exists and has the correct role for this portal
        $user = \App\Models\User::where('email', $encryptedEmail)->first();
        $source = $request->input('source', 'admin');

        if ($user) {
            $isAdminPortal = ($source === 'admin');
            $isStaff = in_array($user->role, ['admin', 'support']);

            if ($isAdminPortal && !$isStaff) {
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Employees must use the Employee Portal for password recovery.']);
            }

            if (!$isAdminPortal && $isStaff) {
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Admins and Support staff must use the main Login portal for password recovery.']);
            }
        }

        $status = Password::sendResetLink(['email' => $encryptedEmail]);

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
