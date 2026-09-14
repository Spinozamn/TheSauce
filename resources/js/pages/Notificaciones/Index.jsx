import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const coloresTipo = {
    info: 'bg-blue-100 text-blue-800',
    exito: 'bg-green-100 text-green-800',
    advertencia: 'bg-yellow-100 text-yellow-800',
    error: 'bg-red-100 text-red-800',
};

const formatoFecha = (fecha) =>
    fecha
        ? new Date(fecha).toLocaleDateString('es-MX', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : '—';

export default function Index({ notificaciones }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Notificaciones
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Título</th>
                            <th className="p-3">Tipo</th>
                            <th className="p-3">Leído</th>
                            <th className="p-3">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        {notificaciones.data.map((notificacion) => (
                            <tr
                                key={notificacion.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{notificacion.id}</td>
                                <td className="p-3 font-medium">
                                    {notificacion.usuario.nombre}{' '}
                                    {notificacion.usuario.apellido_paterno}
                                </td>
                                <td className="p-3">{notificacion.titulo}</td>
                                <td className="p-3">
                                    <span
                                        className={`rounded px-2 py-1 text-xs font-semibold ${coloresTipo[notificacion.tipo]}`}
                                    >
                                        {notificacion.tipo}
                                    </span>
                                </td>
                                <td className="p-3">
                                    <span
                                        className={`rounded px-2 py-1 text-xs font-semibold ${
                                            notificacion.leido
                                                ? 'bg-gray-100 text-gray-800'
                                                : 'bg-orange-100 text-orange-800'
                                        }`}
                                    >
                                        {notificacion.leido ? 'Sí' : 'No'}
                                    </span>
                                </td>
                                <td className="p-3">
                                    {formatoFecha(
                                        notificacion.fecha_notificacion,
                                    )}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={notificaciones} />
            </div>
        </Layout>
    );
}
