import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoMoneda } from '@/lib/formato';
import type { InnovacionVista, Paginador } from '@/types/admin';

interface Props {
    innovaciones: Paginador<InnovacionVista>;
}

export default function Trash({ innovaciones }: Props) {
    const handleRestaurar = (id: number, titulo: string) => {
        if (confirm(`¿Deseas restaurar la innovación #${id} "${titulo}" al listado principal?`)) {
            router.patch(`/admin/innovaciones/${id}/restaurar`);
        }
    };

    const handleForceDelete = (id: number, titulo: string) => {
        if (
            confirm(
                `¿Deseas eliminar definitivamente la innovación #${id} "${titulo}"?\n\nEsta acción borrará el registro de la base de datos y su archivo de imagen en disco.`
            )
        ) {
            router.delete(`/admin/innovaciones/${id}/definitivo`);
        }
    };

    return (
        <AdminLayout>
            <Head title="Papelera de Innovaciones" />

            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 p-6">
                    <div>
                        <h2 className="text-2xl font-bold text-gray-900">
                            Papelera de Innovaciones
                        </h2>
                        <p className="text-sm text-gray-500 mt-1">
                            Registros eliminados lógicamente disponibles para restauración o eliminación física
                        </p>
                    </div>
                    <Link
                        href="/admin/innovaciones"
                        className="rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50"
                    >
                        Volver al Listado Principal
                    </Link>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Título</th>
                                <th className="p-4 font-semibold">Categoría</th>
                                <th className="p-4 font-semibold">Meta</th>
                                <th className="p-4 font-semibold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {innovaciones.data.length > 0 ? (
                                innovaciones.data.map((innovacion) => (
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
                                            {innovacion.categoria?.nombre}
                                        </td>
                                        <td className="p-4 font-medium text-gray-900">
                                            {formatoMoneda(innovacion.meta_financiera)}
                                        </td>
                                        <td className="p-4 text-center">
                                            <div className="flex items-center justify-center gap-2">
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        handleRestaurar(
                                                            innovacion.id,
                                                            innovacion.titulo
                                                        )
                                                    }
                                                    className="rounded-md border border-green-200 bg-white px-3 py-1.5 text-xs font-medium text-green-700 shadow-sm transition-colors hover:bg-green-50"
                                                >
                                                    Restaurar
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        handleForceDelete(
                                                            innovacion.id,
                                                            innovacion.titulo
                                                        )
                                                    }
                                                    className="rounded-md border border-red-300 bg-red-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm transition-colors hover:bg-red-700"
                                                >
                                                    Eliminar Definitivo
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={5} className="p-8 text-center text-sm text-gray-400">
                                        No hay registros en la papelera.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={innovaciones} />
            </div>
        </AdminLayout>
    );
}