import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const coloresEstado = {
    pendiente: 'bg-yellow-100 text-yellow-800',
    aprobada: 'bg-green-100 text-green-800',
    rechazada: 'bg-red-100 text-red-800',
    reembolsada: 'bg-blue-100 text-blue-800',
};

const formatoMoneda = (monto) =>
    Number(monto).toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
    });

const formatoFecha = (fecha) =>
    fecha
        ? new Date(fecha).toLocaleDateString('es-MX', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : '—';

export default function Index({ contribuciones }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Contribuciones
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Innovación</th>
                            <th className="p-3">Monto</th>
                            <th className="p-3">Método de Pago</th>
                            <th className="p-3">Estado</th>
                            <th className="p-3">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        {contribuciones.data.map((contribucion) => (
                            <tr
                                key={contribucion.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{contribucion.id}</td>
                                <td className="p-3 font-medium">
                                    {contribucion.usuario.nombre}{' '}
                                    {contribucion.usuario.apellido_paterno}
                                </td>
                                <td className="p-3">
                                    {contribucion.innovacion.titulo}
                                </td>
                                <td className="p-3">
                                    {formatoMoneda(contribucion.monto)}
                                </td>
                                <td className="p-3">
                                    {contribucion.metodo_pago}
                                </td>
                                <td className="p-3">
                                    <span
                                        className={`rounded px-2 py-1 text-xs font-semibold ${coloresEstado[contribucion.estado]}`}
                                    >
                                        {contribucion.estado}
                                    </span>
                                </td>
                                <td className="p-3">
                                    {formatoFecha(
                                        contribucion.fecha_contribucion,
                                    )}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={contribuciones} />
            </div>
        </Layout>
    );
}
