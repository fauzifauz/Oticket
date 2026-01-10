<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        $user = $request->user();
        $source = $request->input('login_source');

        // Enforce Login Source Policy
        if ($source === 'employee') {
            if (in_array($user->role, ['admin', 'support'])) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Admins and Support staff must use the main Login portal.']);
            }
        } elseif ($source === 'admin') {
            if (!in_array($user->role, ['admin', 'support'])) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('employee.login')->withErrors(['email' => 'Employees must use the Employee Portal.']);
            }
        }

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($user->role === 'support') {
            return redirect()->intended(route('support.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
