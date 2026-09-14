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

export default function Index({ listaDeseos }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Lista de Deseos
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Usuario</th>
                            <th className="p-3">Innovación</th>
                            <th className="p-3">Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        {listaDeseos.data.map((deseo) => (
                            <tr
                                key={deseo.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{deseo.id}</td>
                                <td className="p-3 font-medium">
                                    {deseo.usuario.nombre}{' '}
                                    {deseo.usuario.apellido_paterno}
                                </td>
                                <td className="p-3">
                                    {deseo.innovacion.titulo}
                                </td>
                                <td className="p-3">
                                    {formatoFecha(deseo.created_at)}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={listaDeseos} />
            </div>
        </Layout>
    );
}
