import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoFecha, formatoMoneda } from '@/lib/formato';
import type { ContribucionVista, Paginador } from '@/types/admin';

interface ContribucionesIndexProps {
    contribuciones: Paginador<ContribucionVista>;
}

const coloresEstado: Record<string, string> = {
    pendiente: 'bg-yellow-100 text-yellow-700',
    aprobada: 'bg-green-100 text-green-700',
    rechazada: 'bg-red-100 text-red-700',
    reembolsada: 'bg-blue-100 text-blue-700',
};

export default function ContribucionesIndex({
    contribuciones,
}: ContribucionesIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Contribuciones
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
                                <th className="p-4 font-semibold">Monto</th>
                                <th className="p-4 font-semibold">
                                    Método de Pago
                                </th>
                                <th className="p-4 font-semibold">Estado</th>
                                <th className="p-4 font-semibold">Fecha</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {contribuciones.data.map((contribucion) => (
                                <tr
                                    key={contribucion.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{contribucion.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {contribucion.usuario.nombre}{' '}
                                        {contribucion.usuario.apellido_paterno}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {contribucion.innovacion.titulo}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {formatoMoneda(contribucion.monto)}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {contribucion.metodo_pago}
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                coloresEstado[
                                                    contribucion.estado
                                                ]
                                            }`}
                                        >
                                            {contribucion.estado}
                                        </span>
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {formatoFecha(
                                            contribucion.fecha_contribucion,
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={contribuciones} />
            </div>
        </AdminLayout>
    );
}
