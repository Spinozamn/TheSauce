import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import type { EtiquetaVista, Paginador } from '@/types/admin';

interface EtiquetasIndexProps {
    etiquetas: Paginador<EtiquetaVista>;
}

export default function EtiquetasIndex({ etiquetas }: EtiquetasIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Etiquetas
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Nombre</th>
                                <th className="p-4 font-semibold">Color</th>
                                <th className="p-4 font-semibold">
                                    Innovaciones
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {etiquetas.data.map((etiqueta) => (
                                <tr
                                    key={etiqueta.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{etiqueta.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {etiqueta.nombre}
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className="rounded-full px-2 py-1 text-xs font-semibold text-white"
                                            style={{
                                                backgroundColor: etiqueta.color,
                                            }}
                                        >
                                            {etiqueta.color}
                                        </span>
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {etiqueta.innovaciones_count ?? 0}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={etiquetas} />
            </div>
        </AdminLayout>
    );
}
