import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const formatoMoneda = (monto) =>
    Number(monto).toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
    });

export default function Index({ carritoDetalles }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Detalles de Carrito
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Innovación</th>
                            <th className="p-3">Monto Comprometido</th>
                            <th className="p-3">Recompensa</th>
                            <th className="p-3">Mensaje de Apoyo</th>
                        </tr>
                    </thead>
                    <tbody>
                        {carritoDetalles.data.map((detalle) => (
                            <tr
                                key={detalle.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{detalle.id}</td>
                                <td className="p-3 font-medium">
                                    {detalle.carrito.usuario.nombre}{' '}
                                    {detalle.carrito.usuario.apellido_paterno}
                                </td>
                                <td className="p-3">
                                    {detalle.innovacion.titulo}
                                </td>
                                <td className="p-3">
                                    {formatoMoneda(detalle.monto_comprometido)}
                                </td>
                                <td className="p-3">
                                    {detalle.recompensa_seleccionada}
                                </td>
                                <td className="p-3">
                                    {detalle.mensaje_apoyo ?? '—'}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={carritoDetalles} />
            </div>
        </Layout>
    );
}
