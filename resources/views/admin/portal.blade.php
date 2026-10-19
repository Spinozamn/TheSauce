<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Administración - TheSauce</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">

    <nav class="bg-slate-900 border-b border-slate-800 px-6 py-4 flex items-center justify-between">
        <div class="font-bold text-xl text-white">
            TheSauce <span class="text-blue-500">Admin</span>
        </div>

        <div class="flex items-center gap-4">
            @guest('admin')
                <a href="{{ route('admin.login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-semibold transition-colors">
                    Iniciar Sesión
                </a>
            @endguest

            @auth('admin')
                <div class="flex items-center gap-4">
                    <span class="text-slate-300 text-sm">
                        Administrador: <strong class="text-white">{{ Auth::guard('admin')->user()->nombre }}</strong>
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 ml-1"></span>
                    </span>

                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-red-900/40 hover:bg-red-800 text-red-200 border border-red-700/50 rounded-lg text-sm transition-colors">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-10 px-4">
        @session('success')
            <div class="mb-6 p-4 rounded-xl bg-emerald-950/60 border border-emerald-800 text-emerald-300">
                {{ $value }}
            </div>
        @endsession

        @session('status')
            <div class="mb-6 p-4 rounded-xl bg-blue-950/60 border border-blue-800 text-blue-300">
                {{ $value }}
            </div>
        @endsession

        @guest('admin')
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 text-center shadow-xl">
                <h2 class="text-2xl font-bold text-white mb-2">Acceso Restringido</h2>
                <p class="text-slate-400 mb-6">Debes iniciar sesión con una cuenta de administrador activa para gestionar el sistema.</p>
                <a href="{{ route('admin.login') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg shadow-lg shadow-blue-600/30 transition-all">
                    Ir al Formulario de Inicio de Sesión
                </a>
            </div>
        @endguest

        @auth('admin')
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Panel de Control Administrativo</h2>
                        <p class="text-slate-400 text-sm mt-1">Sesión autenticada mediante el guardia <code class="text-blue-400">admin</code></p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950 text-emerald-300 border border-emerald-800">
                        Cuenta Activa
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('admin.innovaciones.index') }}" class="p-4 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                        <div class="font-semibold text-white">Innovaciones</div>
                        <div class="text-slate-400 text-sm">Gestionar proyectos, metas y papelera</div>
                    </a>
                    <a href="{{ route('admin.categorias.index') }}" class="p-4 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                        <div class="font-semibold text-white">Categorías</div>
                        <div class="text-slate-400 text-sm">Consultar categorías temáticas</div>
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" class="p-4 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                        <div class="font-semibold text-white">Usuarios</div>
                        <div class="text-slate-400 text-sm">Listado general de usuarios</div>
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="p-4 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                        <div class="font-semibold text-white">Roles</div>
                        <div class="text-slate-400 text-sm">Consultar roles y permisos</div>
                    </a>
                </div>
            </div>
        @endauth
    </main>

</body>
</html>
