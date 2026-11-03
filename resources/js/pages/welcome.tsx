import { calcularDiasRestantes, calcularPorcentaje, formatoMoneda } from '@/lib/formato';
import type { CategoriaPublica, InnovacionPublica, Paginador, SesionUsuario } from '@/types/admin';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { Compass, Flame, Layers, LogIn, Search, Sparkles, User, UserPlus } from 'lucide-react';
import { useState } from 'react';

type WelcomeProps = {
    innovaciones: Paginador<InnovacionPublica>;
    destacado?: InnovacionPublica | null;
    categorias: CategoriaPublica[];
    filtros: {
        categoria?: number | null;
        q?: string;
    };
    auth?: {
        user?: SesionUsuario | null;
    };
};

export default function Welcome({
    innovaciones,
    destacado,
    categorias = [],
    filtros,
}: WelcomeProps) {
    const { auth } = usePage<WelcomeProps>().props;
    const [busqueda, setBusqueda] = useState(filtros.q || '');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(
            '/',
            {
                q: busqueda || undefined,
                categoria: filtros.categoria || undefined,
            },
            { preserveState: true }
        );
    };

    const handleCategoriaSelect = (catId?: number | null) => {
        router.get(
            '/',
            {
                categoria: catId || undefined,
                q: busqueda || undefined,
            },
            { preserveState: true }
        );
    };

    return (
        <div className="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100">
            <Head title="TheSauce - Descubre y apoya innovaciones creativas" />

            <header className="sticky top-0 z-40 border-b border-gray-200 bg-white/90 backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/90">
                <div className="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
                    <div className="flex items-center gap-6">
                        <Link href="/" className="flex items-center gap-2">
                            <span className="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-600 text-lg font-black text-white shadow-sm">
                                S
                            </span>
                            <span className="text-xl font-extrabold tracking-tight">TheSauce</span>
                        </Link>

                        <nav className="hidden items-center gap-4 text-sm font-medium md:flex">
                            <button
                                type="button"
                                onClick={() => handleCategoriaSelect(null)}
                                className={`transition-colors ${
                                    !filtros.categoria
                                        ? 'text-emerald-600 font-semibold dark:text-emerald-400'
                                        : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100'
                                }`}
                            >
                                Explorar
                            </button>
                        </nav>
                    </div>

                    <form onSubmit={handleSearch} className="relative hidden max-w-xs flex-1 sm:block">
                        <input
                            type="text"
                            value={busqueda}
                            onChange={(e) => setBusqueda(e.target.value)}
                            placeholder="Buscar proyectos..."
                            className="w-full rounded-full border border-gray-300 bg-gray-100/70 py-1.5 pr-4 pl-9 text-xs outline-none transition focus:border-emerald-500 focus:bg-white dark:border-gray-700 dark:bg-gray-800 dark:focus:border-emerald-400 dark:focus:bg-gray-900"
                        />
                        <Search className="absolute top-2 left-3 h-3.5 w-3.5 text-gray-400" />
                    </form>

                    <div className="flex items-center gap-3">
                        {auth?.user ? (
                            <Link
                                href="/dashboard"
                                className="inline-flex items-center gap-1.5 rounded-full border border-gray-300 px-4 py-1.5 text-xs font-semibold hover:border-gray-400 dark:border-gray-700 dark:hover:border-gray-600"
                            >
                                <User className="h-3.5 w-3.5" />
                                <span>Mi cuenta</span>
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href="/login"
                                    className="inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white"
                                >
                                    <LogIn className="h-3.5 w-3.5" />
                                    <span>Iniciar sesion</span>
                                </Link>
                                <Link
                                    href="/register"
                                    className="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-500"
                                >
                                    <UserPlus className="h-3.5 w-3.5" />
                                    <span>Registrarse</span>
                                </Link>
                            </>
                        )}
                    </div>
                </div>

                <div className="mx-auto flex max-w-7xl items-center gap-2 overflow-x-auto px-4 py-2 sm:px-6">
                    <button
                        type="button"
                        onClick={() => handleCategoriaSelect(null)}
                        className={`shrink-0 rounded-full px-3 py-1 text-xs font-medium transition ${
                            !filtros.categoria
                                ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300'
                        }`}
                    >
                        Todas las categorias
                    </button>
                    {categorias.map((cat) => (
                        <button
                            key={cat.id}
                            type="button"
                            onClick={() => handleCategoriaSelect(cat.id)}
                            className={`shrink-0 rounded-full px-3 py-1 text-xs font-medium transition ${
                                filtros.categoria === cat.id
                                    ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300'
                            }`}
                        >
                            {cat.nombre}
                        </button>
                    ))}
                </div>
            </header>

            <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6">
                {destacado && !filtros.categoria && !filtros.q && (
                    <section className="mb-12">
                        <div className="mb-3 flex items-center gap-2">
                            <Flame className="h-5 w-5 text-amber-500" />
                            <h2 className="text-sm font-bold uppercase tracking-wider text-gray-500">
                                Proyecto destacado
                            </h2>
                        </div>

                        <div className="grid overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm md:grid-cols-12 dark:border-gray-800 dark:bg-gray-900">
                            <div className="relative min-h-[260px] bg-gray-100 md:col-span-7 dark:bg-gray-800">
                                {destacado.imagen_portada ? (
                                    <img
                                        src={destacado.imagen_portada}
                                        alt={destacado.titulo}
                                        className="h-full w-full object-cover"
                                    />
                                ) : (
                                    <div className="flex h-full min-h-[260px] items-center justify-center bg-gradient-to-br from-emerald-500/10 via-gray-100 to-amber-500/10 dark:from-emerald-950/40 dark:via-gray-900 dark:to-amber-950/40">
                                        <Sparkles className="h-16 w-16 text-emerald-600/40" />
                                    </div>
                                )}
                                {destacado.categoria && (
                                    <span className="absolute top-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-gray-800 shadow-sm backdrop-blur-xs dark:bg-gray-900/90 dark:text-gray-200">
                                        {destacado.categoria.nombre}
                                    </span>
                                )}
                            </div>

                            <div className="flex flex-col justify-between p-6 md:col-span-5 md:p-8">
                                <div>
                                    <h3 className="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                                        <Link
                                            href={`/proyectos/${destacado.id}`}
                                            className="hover:text-emerald-600 transition"
                                        >
                                            {destacado.titulo}
                                        </Link>
                                    </h3>
                                    <p className="mt-1 text-xs text-gray-500">
                                        Por {destacado.usuario?.nombre || 'Creador de TheSauce'}
                                    </p>
                                    <p className="mt-3 line-clamp-3 text-sm text-gray-600 dark:text-gray-300">
                                        {destacado.descripcion}
                                    </p>
                                </div>

                                <div className="mt-6 border-t border-gray-100 pt-5 dark:border-gray-800">
                                    <div className="h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800">
                                        <div
                                            className="h-full rounded-full bg-emerald-500 transition-all"
                                            style={{
                                                width: `${Math.min(
                                                    calcularPorcentaje(
                                                        destacado.monto_recaudado,
                                                        destacado.meta_financiera
                                                    ),
                                                    100
                                                )}%`,
                                            }}
                                        />
                                    </div>

                                    <div className="mt-4 grid grid-cols-3 gap-2 text-left">
                                        <div>
                                            <p className="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                                {calcularPorcentaje(
                                                    destacado.monto_recaudado,
                                                    destacado.meta_financiera
                                                )}
                                                %
                                            </p>
                                            <p className="text-[11px] text-gray-500">Financiado</p>
                                        </div>
                                        <div>
                                            <p className="text-lg font-bold text-gray-900 dark:text-gray-100">
                                                {formatoMoneda(destacado.monto_recaudado)}
                                            </p>
                                            <p className="text-[11px] text-gray-500">
                                                de {formatoMoneda(destacado.meta_financiera)}
                                            </p>
                                        </div>
                                        <div>
                                            <p className="text-lg font-bold text-gray-900 dark:text-gray-100">
                                                {calcularDiasRestantes(destacado.fecha_fin)}
                                            </p>
                                            <p className="text-[11px] text-gray-500">Dias restantes</p>
                                        </div>
                                    </div>

                                    <Link
                                        href={`/proyectos/${destacado.id}`}
                                        className="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500"
                                    >
                                        Ver proyecto
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </section>
                )}

                <section>
                    <div className="mb-6 flex items-center justify-between">
                        <div>
                            <h2 className="text-xl font-bold tracking-tight">
                                {filtros.categoria
                                    ? `Proyectos en ${
                                          categorias.find((c) => c.id === filtros.categoria)?.nombre ||
                                          'esta categoria'
                                      }`
                                    : 'Descubrir innovaciones'}
                            </h2>
                            <p className="text-xs text-gray-500">
                                {innovaciones.total} proyectos disponibles para impulsar
                            </p>
                        </div>
                    </div>

                    {innovaciones.data.length === 0 ? (
                        <div className="rounded-2xl border border-dashed border-gray-300 py-16 text-center dark:border-gray-700">
                            <Compass className="mx-auto h-12 w-12 text-gray-400" />
                            <h3 className="mt-3 text-base font-semibold">No se encontraron proyectos</h3>
                            <p className="mt-1 text-xs text-gray-500">
                                Intenta seleccionar otra categoria o cambiar tu termino de busqueda.
                            </p>
                            <button
                                type="button"
                                onClick={() => handleCategoriaSelect(null)}
                                className="mt-4 inline-flex items-center gap-1.5 rounded-full bg-gray-900 px-4 py-1.5 text-xs font-semibold text-white dark:bg-white dark:text-gray-900"
                            >
                                Ver todos
                            </button>
                        </div>
                    ) : (
                        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {innovaciones.data.map((item) => {
                                const percent = calcularPorcentaje(item.monto_recaudado, item.meta_financiera);
                                const days = calcularDiasRestantes(item.fecha_fin);

                                return (
                                    <div
                                        key={item.id}
                                        className="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xs transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900"
                                    >
                                        <Link
                                            href={`/proyectos/${item.id}`}
                                            className="relative aspect-video w-full overflow-hidden bg-gray-100 dark:bg-gray-800"
                                        >
                                            {item.imagen_portada ? (
                                                <img
                                                    src={item.imagen_portada}
                                                    alt={item.titulo}
                                                    className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                                />
                                            ) : (
                                                <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900">
                                                    <Layers className="h-10 w-10 text-gray-400" />
                                                </div>
                                            )}
                                            {item.categoria && (
                                                <span className="absolute top-3 left-3 rounded-full bg-white/90 px-2.5 py-0.5 text-[11px] font-bold text-gray-800 shadow-xs backdrop-blur-xs dark:bg-gray-900/90 dark:text-gray-200">
                                                    {item.categoria.nombre}
                                                </span>
                                            )}
                                        </Link>

                                        <div className="flex flex-1 flex-col justify-between p-5">
                                            <div>
                                                <h3 className="line-clamp-1 text-base font-bold text-gray-900 group-hover:text-emerald-600 transition dark:text-gray-100">
                                                    <Link href={`/proyectos/${item.id}`}>{item.titulo}</Link>
                                                </h3>
                                                <p className="mt-0.5 text-xs text-gray-500">
                                                    Por {item.usuario?.nombre || 'Creador de TheSauce'}
                                                </p>
                                                <p className="mt-2.5 line-clamp-2 text-xs text-gray-600 dark:text-gray-400">
                                                    {item.descripcion}
                                                </p>
                                            </div>

                                            <div className="mt-5 border-t border-gray-100 pt-3 dark:border-gray-800">
                                                <div className="h-1.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800">
                                                    <div
                                                        className="h-full rounded-full bg-emerald-500"
                                                        style={{ width: `${Math.min(percent, 100)}%` }}
                                                    />
                                                </div>

                                                <div className="mt-3 flex items-center justify-between text-xs">
                                                    <div>
                                                        <span className="font-bold text-gray-900 dark:text-gray-100">
                                                            {formatoMoneda(item.monto_recaudado)}
                                                        </span>
                                                        <p className="text-[10px] text-gray-500">
                                                            {percent}% alcanzado
                                                        </p>
                                                    </div>
                                                    <div className="text-right">
                                                        <span className="font-bold text-gray-900 dark:text-gray-100">
                                                            {days}
                                                        </span>
                                                        <p className="text-[10px] text-gray-500">dias restantes</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}

                    {innovaciones.last_page > 1 && (
                        <div className="mt-10 flex items-center justify-center gap-2">
                            {innovaciones.prev_page_url && (
                                <Link
                                    href={innovaciones.prev_page_url}
                                    preserveState
                                    className="rounded-full border border-gray-300 px-4 py-1.5 text-xs font-semibold hover:border-gray-400 dark:border-gray-700"
                                >
                                    Anterior
                                </Link>
                            )}
                            <span className="text-xs text-gray-500">
                                Pagina {innovaciones.current_page} de {innovaciones.last_page}
                            </span>
                            {innovaciones.next_page_url && (
                                <Link
                                    href={innovaciones.next_page_url}
                                    preserveState
                                    className="rounded-full border border-gray-300 px-4 py-1.5 text-xs font-semibold hover:border-gray-400 dark:border-gray-700"
                                >
                                    Siguiente
                                </Link>
                            )}
                        </div>
                    )}
                </section>
            </main>

            <footer className="mt-20 border-t border-gray-200 bg-white py-10 dark:border-gray-800 dark:bg-gray-900">
                <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 sm:flex-row sm:px-6">
                    <div className="flex items-center gap-2">
                        <span className="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-600 text-sm font-bold text-white">
                            S
                        </span>
                        <span className="text-sm font-bold">TheSauce Crowdfunding</span>
                    </div>

                    <p className="text-xs text-gray-500">
                        Plataforma comunitaria para financiar iniciativas e innovaciones.
                    </p>

                    <div className="flex items-center gap-4 text-xs">
                        <Link
                            href="/login"
                            className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        >
                            Acceso al sistema
                        </Link>
                    </div>
                </div>
            </footer>
        </div>
    );
}
