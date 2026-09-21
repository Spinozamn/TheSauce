import { Head, Link } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import { formatoMoneda } from '@/lib/formato';

interface Etiqueta {
    id: number;
    nombre: string;
    color: string;
}

interface Usuario {
    id: number;
    nombre: string;
    apellido_paterno: string;
    correo: string;
}

interface Categoria {
    id: number;
    nombre: string;
}

interface HitoFinanciero {
    id: number;
    titulo: string;
    monto_objetivo: number;
    alcanzado: boolean;
}

interface Comentario {
    id: number;
    contenido: string;
    created_at: string;
    usuario?: Usuario;
}

interface Contribucion {
    id: number;
    monto: number;
    created_at: string;
    usuario?: Usuario;
}

interface InnovacionCompleta {
    id: number;
    titulo: string;
    descripcion: string;
    imagen_portada: string | null;
    meta_financiera: number;
    monto_recaudado: number;
    estado: string;
    fecha_inicio: string;
    fecha_fin: string;
    created_at: string;
    updated_at: string;
    usuario: Usuario;
    categoria: Categoria;
    etiquetas: Etiqueta[];
    hitos_financieros: HitoFinanciero[];
    comentarios: Comentario[];
    contribuciones: Contribucion[];
}

interface Props {
    innovacion: InnovacionCompleta;
}

const coloresEstado: Record<string, string> = {
    borrador: 'bg-gray-100 text-gray-700 border-gray-300',
    activo: 'bg-green-50 text-green-700 border-green-200',
    financiado: 'bg-blue-50 text-blue-700 border-blue-200',
    cancelado: 'bg-red-50 text-red-700 border-red-200',
};

export default function Show({ innovacion }: Props) {
    return (
        <AdminLayout>
            <Head title={`Innovación #${innovacion.id} - ${innovacion.titulo}`} />

            <div className="space-y-6">
                <div className="flex items-center justify-between border-b border-gray-200 pb-5">
                    <div>
                        <div className="flex items-center gap-3">
                            <h1 className="text-2xl font-bold text-gray-900">
                                #{innovacion.id} - {innovacion.titulo}
                            </h1>
                            <span
                                className={`rounded-full border px-3 py-0.5 text-xs font-semibold uppercase ${
                                    coloresEstado[innovacion.estado] || 'bg-gray-100 text-gray-700'
                                }`}
                            >
                                {innovacion.estado}
                            </span>
                        </div>
                        <p className="mt-1 text-sm text-gray-500">
                            Creado por {innovacion.usuario?.nombre} {innovacion.usuario?.apellido_paterno} ({innovacion.usuario?.correo})
                        </p>
                    </div>
                    <div className="flex items-center gap-3">
                        <Link
                            href="/admin/innovaciones"
                            className="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                        >
                            Volver al Listado
                        </Link>
                        <Link
                            href={`/admin/innovaciones/${innovacion.id}/edit`}
                            className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700"
                        >
                            Editar Registro
                        </Link>
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div className="space-y-6 lg:col-span-2">
                        <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-base font-semibold text-gray-900 mb-4">
                                Descripción general
                            </h2>
                            <p className="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
                                {innovacion.descripcion}
                            </p>
                        </div>

                        <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-base font-semibold text-gray-900 mb-4">
                                Hitos Financieros ({innovacion.hitos_financieros?.length || 0})
                            </h2>
                            {innovacion.hitos_financieros && innovacion.hitos_financieros.length > 0 ? (
                                <ul className="divide-y divide-gray-100">
                                    {innovacion.hitos_financieros.map((hito) => (
                                        <li key={hito.id} className="py-3 flex items-center justify-between">
                                            <span className="text-sm font-medium text-gray-800">{hito.titulo}</span>
                                            <div className="flex items-center gap-3">
                                                <span className="text-sm text-gray-600">{formatoMoneda(hito.monto_objetivo)}</span>
                                                <span className={`text-xs px-2 py-0.5 rounded-full ${hito.alcanzado ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}`}>
                                                    {hito.alcanzado ? 'Alcanzado' : 'Pendiente'}
                                                </span>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            ) : (
                                <p className="text-sm text-gray-400">No hay hitos registrados para este proyecto.</p>
                            )}
                        </div>

                        <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-base font-semibold text-gray-900 mb-4">
                                Contribuciones recibidas ({innovacion.contribuciones?.length || 0})
                            </h2>
                            {innovacion.contribuciones && innovacion.contribuciones.length > 0 ? (
                                <ul className="divide-y divide-gray-100">
                                    {innovacion.contribuciones.map((c) => (
                                        <li key={c.id} className="py-3 flex items-center justify-between">
                                            <span className="text-sm text-gray-700">{c.usuario?.nombre} {c.usuario?.apellido_paterno}</span>
                                            <span className="text-sm font-semibold text-gray-900">{formatoMoneda(c.monto)}</span>
                                        </li>
                                    ))}
                                </ul>
                            ) : (
                                <p className="text-sm text-gray-400">Sin aportaciones registradas.</p>
                            )}
                        </div>
                    </div>

                    <div className="space-y-6">
                        <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <h2 className="text-base font-semibold text-gray-900 mb-3">
                                Imagen de Portada
                            </h2>
                            {innovacion.imagen_portada ? (
                                <div className="overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                    <img
                                        src={innovacion.imagen_portada}
                                        alt={innovacion.titulo}
                                        className="h-48 w-full object-cover"
                                    />
                                    <div className="p-2 text-center text-xs text-gray-500 break-all">
                                        {innovacion.imagen_portada}
                                    </div>
                                </div>
                            ) : (
                                <div className="flex h-36 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-sm text-gray-400">
                                    Sin imagen asignada
                                </div>
                            )}
                        </div>

                        <div className="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
                            <h2 className="text-base font-semibold text-gray-900 border-b border-gray-100 pb-2">
                                Métricas y Metadatos
                            </h2>

                            <div>
                                <span className="block text-xs uppercase text-gray-400">Categoría</span>
                                <span className="text-sm font-medium text-gray-800">{innovacion.categoria?.nombre}</span>
                            </div>

                            <div>
                                <span className="block text-xs uppercase text-gray-400">Meta Financiera</span>
                                <span className="text-base font-bold text-gray-900">{formatoMoneda(innovacion.meta_financiera)}</span>
                            </div>

                            <div>
                                <span className="block text-xs uppercase text-gray-400">Monto Recaudado</span>
                                <span className="text-base font-bold text-green-600">{formatoMoneda(innovacion.monto_recaudado)}</span>
                            </div>

                            <div className="grid grid-cols-2 gap-2">
                                <div>
                                    <span className="block text-xs uppercase text-gray-400">Fecha Inicio</span>
                                    <span className="text-sm text-gray-700">{innovacion.fecha_inicio}</span>
                                </div>
                                <div>
                                    <span className="block text-xs uppercase text-gray-400">Fecha Fin</span>
                                    <span className="text-sm text-gray-700">{innovacion.fecha_fin}</span>
                                </div>
                            </div>

                            <div>
                                <span className="block text-xs uppercase text-gray-400 mb-1">Etiquetas</span>
                                <div className="flex flex-wrap gap-1">
                                    {innovacion.etiquetas && innovacion.etiquetas.length > 0 ? (
                                        innovacion.etiquetas.map((e) => (
                                            <span
                                                key={e.id}
                                                className="rounded-full px-2.5 py-0.5 text-xs font-semibold text-white"
                                                style={{ backgroundColor: e.color }}
                                            >
                                                {e.nombre}
                                            </span>
                                        ))
                                    ) : (
                                        <span className="text-xs text-gray-400">Ninguna</span>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}