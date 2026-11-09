<?php

namespace App\Providers;

use App\Models\Administrador;
use App\Models\Innovacion;
use App\Models\User;
use App\Models\Usuario;
use App\Policies\ClienteInnovacionPolicy;
use App\Policies\InnovacionAdminPolicy;
use App\Policies\InnovacionPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->configureDefaults();

        Gate::policy(Innovacion::class, InnovacionPolicy::class);

        Gate::define('manage-own-resource', function (User $user, Innovacion $recurso) {
            if ($recurso->user_id !== null && (int) $recurso->user_id === (int) $user->id) {
                return true;
            }
            $usuario = Usuario::where('email', $user->email)->first();
            if ($usuario && (int) $recurso->usuario_id === (int) $usuario->id) {
                return true;
            }
            return false;
        });

        Gate::define('consultar', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            if (!$user instanceof Administrador || !$user->activo || $user->nivel < 1) {
                return false;
            }
            return !method_exists($user, 'currentAccessToken') || !$user->currentAccessToken() || $user->tokenCan('innovaciones:consultar');
        });

        Gate::define('crear', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            if (!$user instanceof Administrador || !$user->activo || $user->nivel < 2) {
                return false;
            }
            return !method_exists($user, 'currentAccessToken') || !$user->currentAccessToken() || $user->tokenCan('innovaciones:crear');
        });

        Gate::define('editar', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            if (!$user instanceof Administrador || !$user->activo || $user->nivel < 2) {
                return false;
            }
            return !method_exists($user, 'currentAccessToken') || !$user->currentAccessToken() || $user->tokenCan('innovaciones:editar');
        });

        Gate::define('eliminar', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            if (!$user instanceof Administrador || !$user->activo || $user->nivel < 3) {
                return false;
            }
            return !method_exists($user, 'currentAccessToken') || !$user->currentAccessToken() || $user->tokenCan('innovaciones:eliminar');
        });

        Gate::define('administrar-cuentas', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            if (!$user instanceof Administrador || !$user->activo || $user->nivel < 3) {
                return false;
            }
            return !method_exists($user, 'currentAccessToken') || !$user->currentAccessToken() || $user->tokenCan('administradores:gestionar');
        });
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
