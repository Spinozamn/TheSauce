import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const formatoFecha = (fecha) =>
    fecha
        ? new Date(fecha).toLocaleDateString('es-MX', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : '—';

export default function Index({ comentarios }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Comentarios
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Innovación</th>
                            <th className="p-3">Respuesta a</th>
                            <th className="p-3">Contenido</th>
                            <th className="p-3">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        {comentarios.data.map((comentario) => (
                            <tr
                                key={comentario.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{comentario.id}</td>
                                <td className="p-3 font-medium">
                                    {comentario.usuario.nombre}{' '}
                                    {comentario.usuario.apellido_paterno}
                                </td>
                                <td className="p-3">
                                    {comentario.innovacion.titulo}
                                </td>
                                <td className="p-3">
                                    {comentario.padre
                                        ? `#${comentario.padre.id}`
                                        : '—'}
                                </td>
                                <td className="max-w-xs truncate p-3">
                                    {comentario.contenido}
                                </td>
                                <td className="p-3">
                                    {formatoFecha(comentario.fecha_comentario)}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={comentarios} />
            </div>
        </Layout>
    );
}
