import AdminLayout from '@/layouts/admin-layout';
import Paginacion from '@/components/paginacion';
import { formatoFecha, formatoMoneda } from '@/lib/formato';
import type { HitoFinancieroVista, Paginador } from '@/types/admin';

interface HitosFinancierosIndexProps {
    hitosFinancieros: Paginador<HitoFinancieroVista>;
}

const coloresEstado: Record<string, string> = {
    pendiente: 'bg-yellow-100 text-yellow-700',
    cumplido: 'bg-green-100 text-green-700',
    vencido: 'bg-red-100 text-red-700',
};

export default function HitosFinancierosIndex({
    hitosFinancieros,
}: HitosFinancierosIndexProps) {
    return (
        <AdminLayout>
            <div className="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                <div className="border-b border-gray-100 bg-gray-50/50 p-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        Listado de Hitos Financieros
                    </h2>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-sm tracking-wider text-gray-600 uppercase">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">
                                    Innovación
                                </th>
                                <th className="p-4 font-semibold">Título</th>
                                <th className="p-4 font-semibold">
                                    Monto Objetivo
                                </th>
                                <th className="p-4 font-semibold">
                                    Fecha Límite
                                </th>
                                <th className="p-4 font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {hitosFinancieros.data.map((hito) => (
                                <tr
                                    key={hito.id}
                                    className="transition-colors hover:bg-gray-50/80"
                                >
                                    <td className="p-4 text-gray-600">
                                        #{hito.id}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {hito.innovacion.titulo}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {hito.titulo}
                                    </td>
                                    <td className="p-4 font-medium text-gray-900">
                                        {formatoMoneda(hito.monto_objetivo)}
                                    </td>
                                    <td className="p-4 text-gray-600">
                                        {formatoFecha(hito.fecha_limite)}
                                    </td>
                                    <td className="p-4">
                                        <span
                                            className={`rounded-full px-3 py-1 text-xs font-semibold ${
                                                coloresEstado[hito.estado]
                                            }`}
                                        >
                                            {hito.estado}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <Paginacion paginador={hitosFinancieros} />
            </div>
        </AdminLayout>
    );
}
