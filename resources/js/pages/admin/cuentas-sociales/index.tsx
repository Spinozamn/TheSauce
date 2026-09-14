import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoFecha } from '@/lib/formato';
import type { CuentaSocialVista, Paginador } from '@/types/admin';

interface CuentasSocialesIndexProps {
    cuentasSociales: Paginador<CuentaSocialVista>;
}

export default function CuentasSocialesIndex({
    cuentasSociales,
}: CuentasSocialesIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Cuentas Sociales
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Usuario</th>
                                <th className="p-4 font-semibold">Proveedor</th>
                                <th className="p-4 font-semibold">
                                    ID del Proveedor
                                </th>
                                <th className="p-4 font-semibold">Expira</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {cuentasSociales.data.map((cuenta) => (
                                <tr
                                    key={cuenta.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{cuenta.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {cuenta.usuario.nombre}{' '}
                                        {cuenta.usuario.apellido_paterno}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {cuenta.proveedor}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {cuenta.id_proveedor}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {formatoFecha(cuenta.expira_en)}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={cuentasSociales} />
            </div>
        </AdminLayout>
    );
}
