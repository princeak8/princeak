<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Requests\Auth\LoginRequest;

class AdminAuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            
            $request->session()->regenerate();
            return redirect()->intended(route('admin-dashboard', absolute: false));
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    private function redirect($roleId)
    {
        switch($roleId) {
            case Role::superAdmin()->id : return redirect()->intended(route('dashboard', absolute: false)); break;
            case Role::admin()->id : return redirect()->intended(route('dashboard', absolute: false)); break;
            case Role::manager()->id : return redirect()->intended(route('dashboard', absolute: false)); break;
            case Role::salesAssistant()->id : return redirect()->intended(route('dashboard', absolute: false)); break;
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
}
