<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // Redirect authenticated users to their respective dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user()->role);
        }

        return view('backend.login');
    }

    /**
     * Handle an incoming authentication request.
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();
    if (Auth::user()->role == 'admin') {
        return redirect()->intended(route('dashboard', absolute: false));
    } elseif (Auth::user()->role == 'kitchen' || Auth::user()->role == 'admin') {
        return redirect()->intended(route('kitchen-dashboard', absolute: false));
    } else {
        return redirect()->intended(route('/', absolute: false));
    }
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
    private function redirectToDashboard(string|null $role): RedirectResponse
    {
        switch ($role) {
            case 'admin':
                return redirect()->intended(route('dashboard', absolute: false));
            case 'kitchen':
                return redirect()->intended(route('kitchen-dashboard', absolute: false));
            default:
                return redirect()->intended(route('/', absolute: false));
        }
    }
}
