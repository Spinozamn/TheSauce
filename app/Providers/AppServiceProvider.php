<?php

namespace App\Providers;

use App\Models\Administrador;
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

        Gate::define('consultar', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            return $user instanceof Administrador && $user->activo && $user->nivel >= 1;
        });

        Gate::define('crear', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            return $user instanceof Administrador && $user->activo && $user->nivel >= 2;
        });

        Gate::define('editar', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            return $user instanceof Administrador && $user->activo && $user->nivel >= 2;
        });

        Gate::define('eliminar', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            return $user instanceof Administrador && $user->activo && $user->nivel >= 3;
        });

        Gate::define('administrar-cuentas', function ($user = null) {
            $user = $user ?? Auth::guard('admin')->user();
            return $user instanceof Administrador && $user->activo && $user->nivel >= 3;
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
