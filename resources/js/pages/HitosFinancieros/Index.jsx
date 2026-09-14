import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const coloresEstado = {
    pendiente: 'bg-yellow-100 text-yellow-800',
    cumplido: 'bg-green-100 text-green-800',
    vencido: 'bg-red-100 text-red-800',
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

export default function Index({ hitosFinancieros }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Hitos Financieros
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Innovación</th>
                            <th className="p-3">Título</th>
                            <th className="p-3">Monto Objetivo</th>
                            <th className="p-3">Fecha Límite</th>
                            <th className="p-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        {hitosFinancieros.data.map((hito) => (
                            <tr
                                key={hito.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{hito.id}</td>
                                <td className="p-3 font-medium">
                                    {hito.innovacion.titulo}
                                </td>
                                <td className="p-3">{hito.titulo}</td>
                                <td className="p-3">
                                    {formatoMoneda(hito.monto_objetivo)}
                                </td>
                                <td className="p-3">
                                    {formatoFecha(hito.fecha_limite)}
                                </td>
                                <td className="p-3">
                                    <span
                                        className={`rounded px-2 py-1 text-xs font-semibold ${coloresEstado[hito.estado]}`}
                                    >
                                        {hito.estado}
                                    </span>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={hitosFinancieros} />
            </div>
        </Layout>
    );
}
