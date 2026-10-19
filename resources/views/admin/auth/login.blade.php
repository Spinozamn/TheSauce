<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo - TheSauce</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-white">
                TheSauce <span class="text-blue-500">Admin</span>
            </h1>
            <p class="text-slate-400 text-sm mt-2">
                Autenticación de Administradores
            </p>
        </div>

        @session('status')
            <div class="mb-4 p-3 rounded-lg bg-blue-950/60 border border-blue-800 text-blue-300 text-sm">
                {{ $value }}
            </div>
        @endsession

        @session('success')
            <div class="mb-4 p-3 rounded-lg bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-sm">
                {{ $value }}
            </div>
        @endsession

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-950/60 border border-red-800 text-red-300 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="usuario" class="block text-sm font-medium text-slate-300 mb-1">
                    Nombre de Usuario
                </label>
                <input 
                    type="text" 
                    id="usuario" 
                    name="usuario" 
                    value="{{ old('usuario') }}" 
                    required 
                    autofocus
                    placeholder="Ej. admin_general"
                    class="w-full px-4 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1">
                    Contraseña
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                >
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer text-slate-400">
                    <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-blue-500">
                    Recordar sesión
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full py-3 px-4 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold shadow-lg shadow-blue-600/30 transition-all duration-200"
            >
                Iniciar Sesión
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-800 pt-4">
            @guest('admin')
                <span>Panel Administrativo protegido</span>
            @endguest
            @auth('admin')
                <span class="text-emerald-400">Sesión activa como {{ Auth::guard('admin')->user()->nombre }}</span>
            @endauth
        </div>
    </div>

</body>
</html>
