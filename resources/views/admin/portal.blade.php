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
                        {{ Auth::guard('admin')->user()->nombre }}
                        <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ Auth::guard('admin')->user()->nivel === 3 ? 'bg-purple-950 text-purple-300 border border-purple-800' : (Auth::guard('admin')->user()->nivel === 2 ? 'bg-blue-950 text-blue-300 border border-blue-800' : 'bg-slate-800 text-slate-300 border border-slate-700') }}">
                            Nivel {{ Auth::guard('admin')->user()->nivel }}
                        </span>
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

    <main class="max-w-5xl mx-auto py-10 px-4">
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
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-xl space-y-8">
                <div class="flex items-center justify-between border-b border-slate-800 pb-5">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Panel de Control por Niveles</h2>
                        <p class="text-slate-400 text-sm mt-1">
                            Sesión activa como <strong class="text-slate-200">{{ Auth::guard('admin')->user()->usuario }}</strong>
                        </p>
                    </div>
                    <div class="text-right">
                        @if (Auth::guard('admin')->user()->nivel === 3)
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-purple-950 text-purple-300 border border-purple-800">
                                Superadministrador (Nivel 3)
                            </span>
                        @elseif (Auth::guard('admin')->user()->nivel === 2)
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-950 text-blue-300 border border-blue-800">
                                Administrador Editor (Nivel 2)
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                                Administrador Consulta (Nivel 1)
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-3">Módulos de Consulta General</h3>
                    @can('consultar')
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <a href="{{ route('admin.innovaciones.index') }}" class="p-4 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                                <div class="font-semibold text-white">Innovaciones</div>
                                <div class="text-slate-400 text-xs mt-1">Listado de proyectos</div>
                            </a>
                            <a href="{{ route('admin.categorias.index') }}" class="p-4 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                                <div class="font-semibold text-white">Categorías</div>
                                <div class="text-slate-400 text-xs mt-1">Listado de categorías</div>
                            </a>
                            <a href="{{ route('admin.usuarios.index') }}" class="p-4 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                                <div class="font-semibold text-white">Usuarios</div>
                                <div class="text-slate-400 text-xs mt-1">Directorio de usuarios</div>
                            </a>
                            <a href="{{ route('admin.roles.index') }}" class="p-4 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                                <div class="font-semibold text-white">Roles</div>
                                <div class="text-slate-400 text-xs mt-1">Catálogo de roles</div>
                            </a>
                        </div>
                    @endcan
                </div>

                <div class="border-t border-slate-800 pt-6">
                    <h3 class="text-lg font-semibold text-white mb-3">Operaciones de Creación y Edición</h3>
                    @can('crear')
                        <div class="flex items-center justify-between p-4 rounded-xl bg-blue-950/30 border border-blue-900/50">
                            <div>
                                <div class="font-semibold text-white">Registro de Innovaciones</div>
                                <div class="text-slate-400 text-xs mt-0.5">Autorizado para crear y modificar proyectos del sistema</div>
                            </div>
                            <a href="{{ route('admin.innovaciones.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-semibold transition-colors">
                                Nueva Innovación
                            </a>
                        </div>
                    @endcan

                    @cannot('crear')
                        <div class="p-4 rounded-xl bg-slate-800/30 border border-slate-800 flex items-center justify-between text-slate-500">
                            <div>
                                <div class="font-medium">Registro de Innovaciones</div>
                                <div class="text-xs mt-0.5">Operación reservada para administradores de Nivel 2 o superior</div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-800 text-slate-400">
                                No Autorizado
                            </span>
                        </div>
                    @endcannot
                </div>

                <div class="border-t border-slate-800 pt-6">
                    <h3 class="text-lg font-semibold text-white mb-3">Operaciones de Eliminación y Depuración</h3>
                    @can('eliminar')
                        <div class="flex items-center justify-between p-4 rounded-xl bg-amber-950/30 border border-amber-900/50">
                            <div>
                                <div class="font-semibold text-white">Papelera de Reciclaje y Borrado</div>
                                <div class="text-slate-400 text-xs mt-0.5">Autorizado para restaurar o eliminar registros definitivamente</div>
                            </div>
                            <a href="{{ route('admin.innovaciones.trash') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg text-sm font-semibold transition-colors">
                                Gestionar Papelera
                            </a>
                        </div>
                    @endcan

                    @cannot('eliminar')
                        <div class="p-4 rounded-xl bg-slate-800/30 border border-slate-800 flex items-center justify-between text-slate-500">
                            <div>
                                <div class="font-medium">Papelera y Eliminación Definitiva</div>
                                <div class="text-xs mt-0.5">Operación reservada exclusivamente para administradores de Nivel 3</div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-800 text-slate-400">
                                Restringido
                            </span>
                        </div>
                    @endcannot
                </div>

                <div class="border-t border-slate-800 pt-6">
                    <h3 class="text-lg font-semibold text-white mb-3">Gestión de Cuentas Administrativas</h3>
                    @can('administrar-cuentas')
                        <div class="p-4 rounded-xl bg-purple-950/30 border border-purple-900/50 flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-white">Control de Administradores</div>
                                <div class="text-slate-400 text-xs mt-0.5">Acceso total a la asignación de niveles y estado de cuentas</div>
                            </div>
                            <span class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-purple-900/60 text-purple-200 border border-purple-700/50">
                                Privilegios Activos
                            </span>
                        </div>
                    @endcan

                    @cannot('administrar-cuentas')
                        <div class="p-4 rounded-xl bg-slate-800/30 border border-slate-800 flex items-center justify-between text-slate-500">
                            <div>
                                <div class="font-medium">Control de Administradores</div>
                                <div class="text-xs mt-0.5">La gestión de cuentas administrativas requiere Nivel 3</div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-800 text-slate-400">
                                Sin Permisos
                            </span>
                        </div>
                    @endcannot
                </div>
            </div>
        @endauth
    </main>

</body>
</html>
