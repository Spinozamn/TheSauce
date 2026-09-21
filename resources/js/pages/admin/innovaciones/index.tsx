import { Link, router } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoMoneda } from '@/lib/formato';
import type { InnovacionVista, Paginador } from '@/types/admin';

interface InnovacionesIndexProps {
    innovaciones: Paginador<InnovacionVista>;
}

const coloresEstado: Record<string, string> = {
    borrador: 'bg-gray-200 text-gray-700',
    activo: 'bg-green-100 text-green-700',
    financiado: 'bg-blue-100 text-blue-700',
    cancelado: 'bg-red-100 text-red-700',
};

export default function InnovacionesIndex({
    innovaciones,
}: InnovacionesIndexProps) {
    const handleEliminar = (id: number, titulo: string) => {
        if (confirm(`¿Deseas enviar la innovación #${id} "${titulo}" a la papelera?`)) {
            router.delete(`/admin/innovaciones/${id}`);
        }
    };

    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 p-6">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">
                            Listado de Innovaciones
                        </h2>
                        <p className="text-sm text-gray-500 mt-1">
                            Consulta, edición y administración de registros activos
                        </p>
                    </div>
                    <div className="flex items-center gap-3">
                        <Link
                            href="/admin/innovaciones/papelera"
                            className="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50"
                        >
                            Papelera
                        </Link>
                        <Link
                            href="/admin/innovaciones/crear"
                            className="flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 font-medium text-white shadow-sm transition-colors hover:bg-blue-700"
                        >
                            + Nueva Innovación
                        </Link>
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Título</th>
                                <th className="p-4 font-semibold">Usuario</th>
                                <th className="p-4 font-semibold">Categoría</th>
                                <th className="p-4 font-semibold">Etiquetas</th>
                                <th className="p-4 font-semibold">Meta</th>
                                <th className="p-4 font-semibold">Recaudado</th>
                                <th className="p-4 font-semibold">Estado</th>
                                <th className="p-4 font-semibold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {innovaciones.data.map((innovacion) => (
                                <tr
                                    key={innovacion.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{innovacion.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {innovacion.titulo}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {innovacion.usuario.nombre}{' '}
                                        {innovacion.usuario.apellido_paterno}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {innovacion.categoria.nombre}
                                    </td>
                                    <td className="p-4">
                                        <div className="flex flex-wrap gap-1">
                                            {innovacion.etiquetas.map(
                                                (etiqueta) => (
                                                    <span
                                                        key={etiqueta.id}
                                                        className="rounded-full px-2 py-1 text-xs font-semibold text-white"
                                                        style={{
                                                            backgroundColor:
                                                                etiqueta.color,
                                                        }}
                                                    >
                                                        {etiqueta.nombre}
                                                    </span>
                                                ),
                                            )}
                                        </div>
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {formatoMoneda(
                                            innovacion.meta_financiera,
                                        )}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {formatoMoneda(
                                            innovacion.monto_recaudado,
                                        )}
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                coloresEstado[innovacion.estado]
                                            }`}
                                        >
                                            {innovacion.estado}
                                        </span>
                                    </td>
                                    <td className="p-4 text-center">
                                        <div className="flex items-center justify-center gap-1.5">
                                            <Link
                                                href={`/admin/innovaciones/${innovacion.id}`}
                                                className="rounded-md border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 hover:text-blue-600"
                                            >
                                                Ver
                                            </Link>
                                            <Link
                                                href={`/admin/innovaciones/${innovacion.id}/edit`}
                                                className="rounded-md border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 hover:text-indigo-600"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    handleEliminar(
                                                        innovacion.id,
                                                        innovacion.titulo,
                                                    )
                                                }
                                                className="rounded-md border border-red-200 bg-white px-2.5 py-1.5 text-xs font-medium text-red-600 shadow-sm transition-colors hover:bg-red-50 hover:border-red-300"
                                            >
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={innovaciones} />
            </div>
        </AdminLayout>
    );
}