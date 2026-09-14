import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import type { Paginador, RolVista } from '@/types/admin';

interface RolesIndexProps {
    roles: Paginador<RolVista>;
}

export default function RolesIndex({ roles }: RolesIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Roles
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Nombre</th>
                                <th className="p-4 font-semibold">
                                    Descripción
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {roles.data.map((rol) => (
                                <tr
                                    key={rol.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{rol.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {rol.nombre}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {rol.descripcion}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={roles} />
            </div>
        </AdminLayout>
    );
}
