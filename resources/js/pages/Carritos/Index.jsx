import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const coloresEstado = {
    activo: 'bg-green-100 text-green-800',
    finalizado: 'bg-blue-100 text-blue-800',
    abandonado: 'bg-gray-100 text-gray-800',
};

const formatoFecha = (fecha) =>
    fecha
        ? new Date(fecha).toLocaleDateString('es-MX', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : '—';

export default function Index({ carritos }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Carritos
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Estado</th>
                            <th className="p-3">Detalles</th>
                            <th className="p-3">Fecha de Creación</th>
                        </tr>
                    </thead>
                    <tbody>
                        {carritos.data.map((carrito) => (
                            <tr
                                key={carrito.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{carrito.id}</td>
                                <td className="p-3 font-medium">
                                    {carrito.usuario.nombre}{' '}
                                    {carrito.usuario.apellido_paterno}
                                </td>
                                <td className="p-3">
                                    <span
                                        className={`rounded px-2 py-1 text-xs font-semibold ${coloresEstado[carrito.estado]}`}
                                    >
                                        {carrito.estado}
                                    </span>
                                </td>
                                <td className="p-3">
                                    {carrito.detalles_count}
                                </td>
                                <td className="p-3">
                                    {formatoFecha(carrito.created_at)}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={carritos} />
            </div>
        </Layout>
    );
}
