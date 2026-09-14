import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import type { LogSistemaVista, Paginador } from '@/types/admin';

interface LogsSistemaIndexProps {
    logsSistema: Paginador<LogSistemaVista>;
}

const formatoFechaHora = (fecha: string): string =>
    new Date(fecha).toLocaleString('es-MX', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });

export default function LogsSistemaIndex({
    logsSistema,
}: LogsSistemaIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Logs del Sistema
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Usuario</th>
                                <th className="p-4 font-semibold">Acción</th>
                                <th className="p-4 font-semibold">
                                    Entidad Afectada
                                </th>
                                <th className="p-4 font-semibold">
                                    IP de Origen
                                </th>
                                <th className="p-4 font-semibold">Fecha</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {logsSistema.data.map((log) => (
                                <tr
                                    key={log.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{log.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {log.usuario
                                            ? `${log.usuario.nombre} ${log.usuario.apellido_paterno}`
                                            : 'Sistema'}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {log.accion}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {log.entidad_afectada ?? '—'}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {log.ip_origen}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {formatoFechaHora(log.fecha_registro)}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={logsSistema} />
            </div>
        </AdminLayout>
    );
}
