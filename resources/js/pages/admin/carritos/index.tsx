import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoFecha } from '@/lib/formato';
import type { CarritoVista, Paginador } from '@/types/admin';

interface CarritosIndexProps {
    carritos: Paginador<CarritoVista>;
}

const coloresEstado: Record<string, string> = {
    activo: 'bg-green-100 text-green-700',
    finalizado: 'bg-blue-100 text-blue-700',
    abandonado: 'bg-gray-200 text-gray-700',
};

export default function CarritosIndex({ carritos }: CarritosIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Carritos
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Usuario</th>
                                <th className="p-4 font-semibold">Estado</th>
                                <th className="p-4 font-semibold">Detalles</th>
                                <th className="p-4 font-semibold">
                                    Fecha de Creación
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {carritos.data.map((carrito) => (
                                <tr
                                    key={carrito.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{carrito.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {carrito.usuario.nombre}{' '}
                                        {carrito.usuario.apellido_paterno}
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                coloresEstado[carrito.estado]
                                            }`}
                                        >
                                            {carrito.estado}
                                        </span>
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {carrito.detalles_count}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {formatoFecha(carrito.created_at)}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={carritos} />
            </div>
        </AdminLayout>
    );
}
