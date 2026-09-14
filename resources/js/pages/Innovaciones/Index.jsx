import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const coloresEstado = {
    borrador: 'bg-gray-100 text-gray-800',
    activo: 'bg-green-100 text-green-800',
    financiado: 'bg-blue-100 text-blue-800',
    cancelado: 'bg-red-100 text-red-800',
};

const formatoMoneda = (monto) =>
    Number(monto).toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
    });

export default function Index({ innovaciones }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Innovaciones
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Título</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Categoría</th>
                            <th className="p-3">Etiquetas</th>
                            <th className="p-3">Meta</th>
                            <th className="p-3">Recaudado</th>
                            <th className="p-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        {innovaciones.data.map((innovacion) => (
                            <tr
                                key={innovacion.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{innovacion.id}</td>
                                <td className="p-3 font-medium">
                                    {innovacion.titulo}
                                </td>
                                <td className="p-3">
                                    {innovacion.usuario.nombre}{' '}
                                    {innovacion.usuario.apellido_paterno}
                                </td>
                                <td className="p-3">
                                    {innovacion.categoria.nombre}
                                </td>
                                <td className="p-3">
                                    <div className="flex flex-wrap gap-1">
                                        {innovacion.etiquetas.map(
                                            (etiqueta) => (
                                                <span
                                                    key={etiqueta.id}
                                                    className="rounded px-2 py-1 text-xs font-semibold text-white"
                                                    style={{
                                                        backgroundColor:
                                                            etiqueta.color,
                                                    }}
                                                >
                                                    {etiqueta.nombre}
                                                </span>
                                            ),
                                        )}
                                    </div>
                                </td>
                                <td className="p-3">
                                    {formatoMoneda(innovacion.meta_financiera)}
                                </td>
                                <td className="p-3">
                                    {formatoMoneda(innovacion.monto_recaudado)}
                                </td>
                                <td className="p-3">
                                    <span
                                        className={`rounded px-2 py-1 text-xs font-semibold ${coloresEstado[innovacion.estado]}`}
                                    >
                                        {innovacion.estado}
                                    </span>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={innovaciones} />
            </div>
        </Layout>
    );
}
