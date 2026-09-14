import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import type { CategoriaVista, Paginador } from '@/types/admin';

interface CategoriasIndexProps {
    categorias: Paginador<CategoriaVista>;
}

const coloresEstado: Record<string, string> = {
    activa: 'bg-green-100 text-green-700',
    inactiva: 'bg-gray-200 text-gray-700',
};

export default function CategoriasIndex({ categorias }: CategoriasIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Categorías
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Nombre</th>
                                <th className="p-4 font-semibold">
                                    Descripción
                                </th>
                                <th className="p-4 font-semibold">
                                    Innovaciones
                                </th>
                                <th className="p-4 font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {categorias.data.map((categoria) => (
                                <tr
                                    key={categoria.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{categoria.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {categoria.nombre}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {categoria.descripcion}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {categoria.innovaciones_count}
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                coloresEstado[categoria.estado]
                                            }`}
                                        >
                                            {categoria.estado}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={categorias} />
            </div>
        </AdminLayout>
    );
}
