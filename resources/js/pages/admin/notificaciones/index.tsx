import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoFecha } from '@/lib/formato';
import type { NotificacionVista, Paginador } from '@/types/admin';

interface NotificacionesIndexProps {
    notificaciones: Paginador<NotificacionVista>;
}

const coloresTipo: Record<string, string> = {
    info: 'bg-blue-100 text-blue-700',
    exito: 'bg-green-100 text-green-700',
    advertencia: 'bg-yellow-100 text-yellow-700',
    error: 'bg-red-100 text-red-700',
};

export default function NotificacionesIndex({
    notificaciones,
}: NotificacionesIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Notificaciones
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Usuario</th>
                                <th className="p-4 font-semibold">Título</th>
                                <th className="p-4 font-semibold">Tipo</th>
                                <th className="p-4 font-semibold">Leído</th>
                                <th className="p-4 font-semibold">Fecha</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {notificaciones.data.map((notificacion) => (
                                <tr
                                    key={notificacion.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{notificacion.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {notificacion.usuario
                                            ? `${notificacion.usuario.nombre} ${notificacion.usuario.apellido_paterno}`
                                            : 'Sistema'}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {notificacion.titulo}
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                coloresTipo[notificacion.tipo]
                                            }`}
                                        >
                                            {notificacion.tipo}
                                        </span>
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                notificacion.leido
                                                    ? 'bg-gray-200 text-gray-700'
                                                    : 'bg-orange-100 text-orange-700'
                                            }`}
                                        >
                                            {notificacion.leido ? 'Sí' : 'No'}
                                        </span>
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {formatoFecha(
                                            notificacion.fecha_notificacion,
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={notificaciones} />
            </div>
        </AdminLayout>
    );
}
