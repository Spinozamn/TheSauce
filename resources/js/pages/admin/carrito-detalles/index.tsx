import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoMoneda } from '@/lib/formato';
import type { CarritoDetalleVista, Paginador } from '@/types/admin';

interface CarritoDetallesIndexProps {
    carritoDetalles: Paginador<CarritoDetalleVista>;
}

export default function CarritoDetallesIndex({
    carritoDetalles,
}: CarritoDetallesIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Detalles de Carrito
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Usuario</th>
                                <th className="p-4 font-semibold">
                                    Innovación
                                </th>
                                <th className="p-4 font-semibold">
                                    Monto Comprometido
                                </th>
                                <th className="p-4 font-semibold">
                                    Recompensa
                                </th>
                                <th className="p-4 font-semibold">
                                    Mensaje de Apoyo
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {carritoDetalles.data.map((detalle) => (
                                <tr
                                    key={detalle.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{detalle.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {detalle.carrito.usuario.nombre}{' '}
                                        {
                                            detalle.carrito.usuario
                                                .apellido_paterno
                                        }
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {detalle.innovacion.titulo}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {formatoMoneda(
                                            detalle.monto_comprometido,
                                        )}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {detalle.recompensa_seleccionada}
                                    </td>
                                    <td className="max-w-xs truncate p-4 text-gray-600">
                                        {detalle.mensaje_apoyo ?? '—'}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={carritoDetalles} />
            </div>
        </AdminLayout>
    );
}
