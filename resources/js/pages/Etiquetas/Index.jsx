import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

export default function Index({ etiquetas }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Etiquetas
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Nombre</th>
                            <th className="p-3">Color</th>
                            <th className="p-3">Innovaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {etiquetas.data.map((etiqueta) => (
                            <tr
                                key={etiqueta.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{etiqueta.id}</td>
                                <td className="p-3 font-medium">
                                    {etiqueta.nombre}
                                </td>
                                <td className="p-3">
                                    <span
                                        className="rounded px-2 py-1 text-xs font-semibold text-white"
                                        style={{
                                            backgroundColor: etiqueta.color,
                                        }}
                                    >
                                        {etiqueta.color}
                                    </span>
                                </td>
                                <td className="p-3">
                                    {etiqueta.innovaciones_count}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={etiquetas} />
            </div>
        </Layout>
    );
}
