import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoFecha } from '@/lib/formato';
import type { ListaDeseoVista, Paginador } from '@/types/admin';

interface ListaDeseosIndexProps {
    listaDeseos: Paginador<ListaDeseoVista>;
}

export default function ListaDeseosIndex({
    listaDeseos,
}: ListaDeseosIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Lista de Deseos
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
                                    Fecha de Registro
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {listaDeseos.data.map((deseo) => (
                                <tr
                                    key={deseo.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{deseo.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {deseo.usuario.nombre}{' '}
                                        {deseo.usuario.apellido_paterno}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {deseo.innovacion.titulo}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {formatoFecha(deseo.created_at)}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={listaDeseos} />
            </div>
        </AdminLayout>
    );
}
