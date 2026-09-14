import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import type { Paginador, UsuarioVista } from '@/types/admin';

interface UsuariosIndexProps {
    usuarios: Paginador<UsuarioVista>;
}

const coloresEstado: Record<string, string> = {
    activo: 'bg-green-100 text-green-700',
    inactivo: 'bg-gray-200 text-gray-700',
    suspendido: 'bg-red-100 text-red-700',
};

export default function UsuariosIndex({ usuarios }: UsuariosIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Usuarios
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">
                                    Nombre Completo
                                </th>
                                <th className="p-4 font-semibold">Email</th>
                                <th className="p-4 font-semibold">Teléfono</th>
                                <th className="p-4 font-semibold">Rol</th>
                                <th className="p-4 font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {usuarios.data.map((usuario) => (
                                <tr
                                    key={usuario.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{usuario.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {usuario.nombre}{' '}
                                        {usuario.apellido_paterno}{' '}
                                        {usuario.apellido_materno ?? ''}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {usuario.email}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {usuario.telefono ?? '—'}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {usuario.rol.nombre}
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                coloresEstado[usuario.estado]
                                            }`}
                                        >
                                            {usuario.estado}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={usuarios} />
            </div>
        </AdminLayout>
    );
}
