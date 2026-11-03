import { calcularDiasRestantes, calcularPorcentaje, formatoFecha, formatoMoneda } from '@/lib/formato';
import type { InnovacionPublica, SesionUsuario } from '@/types/admin';
import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ChevronRight, Heart, LogIn, Sparkles, Target, User } from 'lucide-react';
import { useState } from 'react';

type ShowProps = {
    proyecto: InnovacionPublica;
    relacionados: InnovacionPublica[];
    auth?: {
        user?: SesionUsuario | null;
    };
};

export default function ShowProyecto({ proyecto, relacionados = [] }: ShowProps) {
    const { auth } = usePage<ShowProps>().props;
    const [seccionActiva, setSeccionActiva] = useState<'historia' | 'hitos' | 'comentarios'>('historia');
    const [guardadoDeseos, setGuardadoDeseos] = useState(false);

    const percent = calcularPorcentaje(proyecto.monto_recaudado, proyecto.meta_financiera);
    const dias = calcularDiasRestantes(proyecto.fecha_fin);
    const totalPatrocinadores = proyecto.contribuciones?.length ?? 0;

    return (
        <div className="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100">
            <Head title={`${proyecto.titulo} - TheSauce`} />

            <header className="sticky top-0 z-40 border-b border-gray-200 bg-white/90 backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/90">
                <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6">
                    <div className="flex items-center gap-3">
                        <Link
                            href="/"
                            className="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        >
                            <ArrowLeft className="h-4 w-4" />
                            <span>Volver a explorar</span>
                        </Link>
                    </div>

                    <Link href="/" className="flex items-center gap-2">
                        <span className="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-sm font-black text-white">
                            S
                        </span>
                        <span className="text-lg font-bold">TheSauce</span>
                    </Link>

                    <div className="flex items-center gap-2">
                        {auth?.user ? (
                            <Link
                                href="/dashboard"
                                className="inline-flex items-center gap-1.5 rounded-full border border-gray-300 px-3 py-1.5 text-xs font-semibold hover:border-gray-400 dark:border-gray-700 dark:hover:border-gray-600"
                            >
                                <User className="h-3.5 w-3.5" />
                                <span>Mi cuenta</span>
                            </Link>
                        ) : (
                            <Link
                                href="/login"
                                className="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700"
                            >
                                <LogIn className="h-3.5 w-3.5" />
                                <span>Iniciar sesion</span>
                            </Link>
                        )}
                    </div>
                </div>
            </header>

            <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6">
                <div className="mb-6 flex items-center gap-2 text-xs text-gray-500">
                    <Link href="/" className="hover:underline">Inicio</Link>
                    <ChevronRight className="h-3 w-3" />
                    {proyecto.categoria ? (
                        <Link href={`/?categoria=${proyecto.categoria.id}`} className="hover:underline">
                            {proyecto.categoria.nombre}
                        </Link>
                    ) : (
                        <span>General</span>
                    )}
                    <ChevronRight className="h-3 w-3" />
                    <span className="truncate max-w-[200px] text-gray-800 dark:text-gray-200">{proyecto.titulo}</span>
                </div>

                <div className="mb-8">
                    <h1 className="text-3xl font-extrabold tracking-tight sm:text-4xl text-gray-900 dark:text-white">
                        {proyecto.titulo}
                    </h1>
                    <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Una iniciativa de <span className="font-semibold text-gray-800 dark:text-gray-200">{proyecto.usuario?.nombre || 'Creador de TheSauce'}</span> en la categoria{' '}
                        {proyecto.categoria ? (
                            <Link href={`/?categoria=${proyecto.categoria.id}`} className="font-semibold text-emerald-600 hover:underline dark:text-emerald-400">
                                {proyecto.categoria.nombre}
                            </Link>
                        ) : (
                            <span className="font-semibold">General</span>
                        )}
                        .
                    </p>
                </div>

                <div className="grid gap-8 lg:grid-cols-12">
                    <div className="lg:col-span-8">
                        <div className="relative aspect-video w-full overflow-hidden rounded-2xl bg-gray-100 shadow-sm dark:bg-gray-900">
                            {proyecto.imagen_portada ? (
                                <img
                                    src={proyecto.imagen_portada}
                                    alt={proyecto.titulo}
                                    className="h-full w-full object-cover"
                                />
                            ) : (
                                <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-500/10 via-gray-100 to-amber-500/10 dark:from-emerald-950/40 dark:via-gray-900 dark:to-amber-950/40">
                                    <Sparkles className="h-20 w-20 text-emerald-600/30" />
                                </div>
                            )}
                            {proyecto.categoria && (
                                <span className="absolute top-4 left-4 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-gray-800 shadow-xs backdrop-blur-xs dark:bg-gray-900/90 dark:text-gray-200">
                                    {proyecto.categoria.nombre}
                                </span>
                            )}
                        </div>
                    </div>

                    <div className="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-4 dark:border-gray-800 dark:bg-gray-900">
                        <div>
                            <div className="h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-800">
                                <div
                                    className="h-full rounded-full bg-emerald-500 transition-all"
                                    style={{ width: `${Math.min(percent, 100)}%` }}
                                />
                            </div>

                            <div className="mt-6 space-y-5">
                                <div>
                                    <p className="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">
                                        {formatoMoneda(proyecto.monto_recaudado)}
                                    </p>
                                    <p className="text-xs text-gray-500">
                                        recaudados de la meta de {formatoMoneda(proyecto.meta_financiera)}
                                    </p>
                                </div>

                                <div>
                                    <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                        {totalPatrocinadores}
                                    </p>
                                    <p className="text-xs text-gray-500">patrocinadores que respaldan esta idea</p>
                                </div>

                                <div>
                                    <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                        {dias}
                                    </p>
                                    <p className="text-xs text-gray-500">dias restantes para completar la recaudacion</p>
                                </div>
                            </div>
                        </div>

                        <div className="mt-8 space-y-3">
                            <Link
                                href={auth?.user ? '/dashboard' : '/login'}
                                className="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-500"
                            >
                                Respaldar este proyecto
                            </Link>

                            <button
                                type="button"
                                onClick={() => setGuardadoDeseos(!guardadoDeseos)}
                                className={`inline-flex w-full items-center justify-center gap-2 rounded-xl border py-2.5 text-xs font-semibold transition ${
                                    guardadoDeseos
                                        ? 'border-rose-300 bg-rose-50 text-rose-600 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-400'
                                        : 'border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800'
                                }`}
                            >
                                <Heart className={`h-4 w-4 ${guardadoDeseos ? 'fill-rose-500 text-rose-500' : ''}`} />
                                <span>{guardadoDeseos ? 'Guardado en lista de deseos' : 'Guardar en lista de deseos'}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div className="mt-12 border-b border-gray-200 dark:border-gray-800">
                    <div className="flex gap-6 text-sm font-semibold">
                        <button
                            type="button"
                            onClick={() => setSeccionActiva('historia')}
                            className={`pb-3 transition ${
                                seccionActiva === 'historia'
                                    ? 'border-b-2 border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400'
                                    : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-300'
                            }`}
                        >
                            Historia del proyecto
                        </button>
                        <button
                            type="button"
                            onClick={() => setSeccionActiva('hitos')}
                            className={`pb-3 transition ${
                                seccionActiva === 'hitos'
                                    ? 'border-b-2 border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400'
                                    : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-300'
                            }`}
                        >
                            Hitos financieros ({proyecto.hitos_financieros?.length || 0})
                        </button>
                        <button
                            type="button"
                            onClick={() => setSeccionActiva('comentarios')}
                            className={`pb-3 transition ${
                                seccionActiva === 'comentarios'
                                    ? 'border-b-2 border-emerald-600 text-emerald-600 dark:border-emerald-400 dark:text-emerald-400'
                                    : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-300'
                            }`}
                        >
                            Comunidad ({proyecto.comentarios?.length || 0})
                        </button>
                    </div>
                </div>

                <div className="mt-8 grid gap-8 lg:grid-cols-12">
                    <div className="lg:col-span-8">
                        {seccionActiva === 'historia' && (
                            <div className="prose max-w-none text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                                <h3 className="text-xl font-bold text-gray-900 dark:text-white">Acerca de la iniciativa</h3>
                                <p className="mt-3 whitespace-pre-line">{proyecto.descripcion}</p>
                                <div className="mt-8 rounded-xl bg-gray-100 p-5 dark:bg-gray-900">
                                    <h4 className="font-bold text-gray-900 dark:text-white">Compromiso TheSauce</h4>
                                    <p className="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                        Todos los aportes se canalizan de forma transparente para cumplir con los objetivos estipulados por el creador.
                                    </p>
                                </div>
                            </div>
                        )}

                        {seccionActiva === 'hitos' && (
                            <div className="space-y-4">
                                <h3 className="text-xl font-bold text-gray-900 dark:text-white">Metas y avances del proyecto</h3>
                                {proyecto.hitos_financieros && proyecto.hitos_financieros.length > 0 ? (
                                    proyecto.hitos_financieros.map((hito) => (
                                        <div
                                            key={hito.id}
                                            className="flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
                                        >
                                            <div className="rounded-full bg-emerald-100 p-2 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                                                <Target className="h-5 w-5" />
                                            </div>
                                            <div>
                                                <div className="flex items-center gap-2">
                                                    <h4 className="font-bold text-gray-900 dark:text-white">{hito.titulo}</h4>
                                                    <span className="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                                        Meta: {formatoMoneda(hito.monto_objetivo)}
                                                    </span>
                                                </div>
                                                <p className="mt-1 text-xs text-gray-600 dark:text-gray-400">{hito.descripcion}</p>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-xs text-gray-500">Este proyecto no tiene hitos especificos adicionales publicados.</p>
                                )}
                            </div>
                        )}

                        {seccionActiva === 'comentarios' && (
                            <div className="space-y-4">
                                <h3 className="text-xl font-bold text-gray-900 dark:text-white">Conversacion con la comunidad</h3>
                                {proyecto.comentarios && proyecto.comentarios.length > 0 ? (
                                    proyecto.comentarios.map((c) => (
                                        <div
                                            key={c.id}
                                            className="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
                                        >
                                            <div className="flex items-center justify-between">
                                                <span className="font-bold text-xs">{c.usuario?.nombre || 'Patrocinador'}</span>
                                                <span className="text-[10px] text-gray-400">{formatoFecha(c.created_at)}</span>
                                            </div>
                                            <p className="mt-2 text-xs text-gray-600 dark:text-gray-300">{c.comentario}</p>
                                        </div>
                                    ))
                                ) : (
                                    <p className="text-xs text-gray-500">Aun no hay comentarios en este proyecto. Se el primero en participar.</p>
                                )}
                            </div>
                        )}
                    </div>

                    <div className="space-y-6 lg:col-span-4">
                        <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                            <h4 className="text-sm font-bold uppercase tracking-wider text-gray-500">Creador de la iniciativa</h4>
                            <div className="mt-3 flex items-center gap-3">
                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold dark:bg-emerald-950 dark:text-emerald-400">
                                    {proyecto.usuario?.nombre ? (
                                        proyecto.usuario.nombre.charAt(0).toUpperCase()
                                    ) : (
                                        <User className="h-5 w-5" />
                                    )}
                                </div>
                                <div>
                                    <p className="text-sm font-bold text-gray-900 dark:text-white">{proyecto.usuario?.nombre || 'Creador de TheSauce'}</p>
                                    <p className="text-xs text-gray-500">Iniciativa verificada en TheSauce</p>
                                </div>
                            </div>
                        </div>

                        <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
                            <h4 className="text-sm font-bold uppercase tracking-wider text-gray-500">Detalles de la campana</h4>
                            <div className="mt-3 space-y-2 text-xs">
                                <div className="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                                    <span className="text-gray-500">Fecha de inicio</span>
                                    <span className="font-semibold text-gray-800 dark:text-gray-200">{formatoFecha(proyecto.fecha_inicio)}</span>
                                </div>
                                <div className="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
                                    <span className="text-gray-500">Fecha de cierre</span>
                                    <span className="font-semibold text-gray-800 dark:text-gray-200">{formatoFecha(proyecto.fecha_fin)}</span>
                                </div>
                                <div className="flex justify-between py-1">
                                    <span className="text-gray-500">Estado</span>
                                    <span className="font-semibold uppercase text-emerald-600 dark:text-emerald-400">{proyecto.estado}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {relacionados.length > 0 && (
                    <section className="mt-20 border-t border-gray-200 pt-10 dark:border-gray-800">
                        <h3 className="text-xl font-bold tracking-tight">Otras iniciativas que podrian interesarte</h3>
                        <div className="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {relacionados.map((rel) => (
                                <Link
                                    key={rel.id}
                                    href={`/proyectos/${rel.id}`}
                                    className="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white p-4 shadow-xs transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900"
                                >
                                    <h4 className="font-bold text-sm text-gray-900 group-hover:text-emerald-600 transition dark:text-white">
                                        {rel.titulo}
                                    </h4>
                                    <p className="mt-1 line-clamp-2 text-xs text-gray-500">{rel.descripcion}</p>
                                    <div className="mt-3 flex items-center justify-between text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                        <span>{formatoMoneda(rel.monto_recaudado)} recaudados</span>
                                        <span>{calcularPorcentaje(rel.monto_recaudado, rel.meta_financiera)}%</span>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    </section>
                )}
            </main>
        </div>
    );
}
