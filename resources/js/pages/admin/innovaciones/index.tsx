import AdminLayout from '@/layouts/admin-layout';
import { Link } from '@inertiajs/react';

export default function InnovacionesIndex() {
    // Datos estáticos (Mock Data) como pide la actividad
    const innovaciones = [
        { id: 1, titulo: 'Sistema de riego inteligente con IoT', categoria: 'AgroTech', estado: 'Activo', meta: 75000 },
        { id: 2, titulo: 'App de reciclaje local comunitario', categoria: 'Tecnología', estado: 'Borrador', meta: 30000 },
    ];

    return (
        <AdminLayout>
            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div className="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h2 className="text-2xl font-bold text-gray-900">Listado de Innovaciones</h2>
                    <Link 
                        href="/admin/innovaciones/crear" 
                        className="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm flex items-center gap-2"
                    >
                        + Nueva Innovación
                    </Link>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                            <tr>
                                <th className="p-4 font-semibold">ID</th>
                                <th className="p-4 font-semibold">Título</th>
                                <th className="p-4 font-semibold">Categoría</th>
                                <th className="p-4 font-semibold">Meta ($)</th>
                                <th className="p-4 font-semibold">Estado</th>
                                <th className="p-4 font-semibold text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {innovaciones.map((item) => (
                                <tr key={item.id} className="hover:bg-gray-50/80 transition-colors">
                                    <td className="p-4 text-gray-600">#{item.id}</td>
                                    <td className="p-4 font-medium text-gray-900">{item.titulo}</td>
                                    <td className="p-4 text-gray-600">{item.categoria}</td>
                                    <td className="p-4 text-gray-900 font-medium">${item.meta.toLocaleString()}</td>
                                    <td className="p-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                                            item.estado === 'Activo' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'
                                        }`}>
                                            {item.estado}
                                        </span>
                                    </td>
                                    <td className="p-4 text-right space-x-3">
                                        <button className="text-blue-600 hover:text-blue-800 font-medium text-sm">Editar</button>
                                        <button className="text-red-600 hover:text-red-800 font-medium text-sm">Eliminar</button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AdminLayout>
    );
}