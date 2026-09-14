import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoFecha } from '@/lib/formato';
import type { ComentarioVista, Paginador } from '@/types/admin';

interface ComentariosIndexProps {
    comentarios: Paginador<ComentarioVista>;
}

export default function ComentariosIndex({
    comentarios,
}: ComentariosIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Comentarios
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
                                    Respuesta a
                                </th>
                                <th className="p-4 font-semibold">Contenido</th>
                                <th className="p-4 font-semibold">Fecha</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {comentarios.data.map((comentario) => (
                                <tr
                                    key={comentario.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{comentario.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {comentario.usuario.nombre}{' '}
                                        {comentario.usuario.apellido_paterno}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {comentario.innovacion.titulo}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {comentario.padre
                                            ? `#${comentario.padre.id}`
                                            : '—'}
                                    </td>
                                    <td className="max-w-xs truncate p-4 text-gray-600">
                                        {comentario.contenido}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {formatoFecha(
                                            comentario.fecha_comentario,
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={comentarios} />
            </div>
        </AdminLayout>
    );
}
