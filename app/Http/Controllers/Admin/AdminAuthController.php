<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthController extends Controller
{
    public function showLoginForm(Request $request): View|RedirectResponse|Response
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if ($request->header('X-Inertia')) {
            return Inertia::location(route('admin.login'));
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'usuario'  => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'usuario.required'  => 'El nombre de usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $admin = Administrador::where('usuario', $credentials['usuario'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors([
                'usuario' => 'Las credenciales proporcionadas no son correctas.',
            ])->onlyInput('usuario');
        }

        if (!$admin->activo) {
            return back()->withErrors([
                'usuario' => 'Su cuenta se encuentra inactiva o deshabilitada. Contacte a soporte.',
            ])->onlyInput('usuario');
        }

        Auth::guard('admin')->login($admin, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Bienvenido al panel, ' . $admin->nombre);
    }

    public function logout(Request $request): Response
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $request->session()->flash('status', 'Has cerrado sesión exitosamente.');

        return Inertia::location(route('admin.login'));
    }
}
