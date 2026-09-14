import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const formatoFecha = (fecha) =>
    fecha
        ? new Date(fecha).toLocaleString('es-MX', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit',
          })
        : '—';

export default function Index({ logsSistema }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Logs del Sistema
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Acción</th>
                            <th className="p-3">Entidad Afectada</th>
                            <th className="p-3">IP de Origen</th>
                            <th className="p-3">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        {logsSistema.data.map((log) => (
                            <tr
                                key={log.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{log.id}</td>
                                <td className="p-3 font-medium">
                                    {log.usuario
                                        ? `${log.usuario.nombre} ${log.usuario.apellido_paterno}`
                                        : 'Sistema'}
                                </td>
                                <td className="p-3">{log.accion}</td>
                                <td className="p-3">
                                    {log.entidad_afectada ?? '—'}
                                </td>
                                <td className="p-3">{log.ip_origen}</td>
                                <td className="p-3">
                                    {formatoFecha(log.fecha_registro)}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={logsSistema} />
            </div>
        </Layout>
    );
}
