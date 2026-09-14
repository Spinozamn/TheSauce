import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const formatoFecha = (fecha) =>
    fecha
        ? new Date(fecha).toLocaleDateString('es-MX', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : '—';

export default function Index({ cuentasSociales }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Cuentas Sociales
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Proveedor</th>
                            <th className="p-3">ID del Proveedor</th>
                            <th className="p-3">Expira</th>
                        </tr>
                    </thead>
                    <tbody>
                        {cuentasSociales.data.map((cuenta) => (
                            <tr
                                key={cuenta.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{cuenta.id}</td>
                                <td className="p-3 font-medium">
                                    {cuenta.usuario.nombre}{' '}
                                    {cuenta.usuario.apellido_paterno}
                                </td>
                                <td className="p-3">{cuenta.proveedor}</td>
                                <td className="p-3">{cuenta.id_proveedor}</td>
                                <td className="p-3">
                                    {formatoFecha(cuenta.expira_en)}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={cuentasSociales} />
            </div>
        </Layout>
    );
}
