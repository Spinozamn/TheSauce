import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

export default function Index({ roles }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Roles
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Nombre</th>
                            <th className="p-3">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        {roles.data.map((rol) => (
                            <tr
                                key={rol.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{rol.id}</td>
                                <td className="p-3 font-medium">
                                    {rol.nombre}
                                </td>
                                <td className="p-3">{rol.descripcion}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={roles} />
            </div>
        </Layout>
    );
}
