<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class UnifiedLoginController extends Controller
{
    public function showLoginForm(Request $request): Response|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('auth/login', [
            'status' => $request->session()->get('status'),
            'canResetPassword' => true,
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo o nombre de usuario es obligatorio.',
            'password.required' => 'La contrasena es obligatoria.',
        ]);

        $loginInput = trim($credentials['email']);
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        $admin = Administrador::where('usuario', $loginInput)
            ->orWhere('email', $loginInput)
            ->first();

        if ($admin && Hash::check($password, $admin->password)) {
            if (!$admin->activo) {
                throw ValidationException::withMessages([
                    'email' => 'Su cuenta se encuentra inactiva o deshabilitada. Contacte a soporte.',
                ]);
            }

            Auth::guard('admin')->login($admin, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        $user = User::where('email', $loginInput)
            ->orWhere('name', $loginInput)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::guard('web')->login($user, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
