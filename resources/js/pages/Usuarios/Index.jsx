import Layout from '@/components/Layout';
import Paginacion from '@/components/Paginacion';

const coloresEstado = {
    activo: 'bg-green-100 text-green-800',
    inactivo: 'bg-gray-100 text-gray-800',
    suspendido: 'bg-red-100 text-red-800',
};

export default function Index({ usuarios }) {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Listado de Usuarios
                </h2>
                <table className="w-full border-collapse text-left">
                    <thead>
                        <tr className="border-b bg-gray-100">
                            <th className="p-3">ID</th>
                            <th className="p-3">Nombre Completo</th>
                            <th className="p-3">Email</th>
                            <th className="p-3">Rol</th>
                            <th className="p-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        {usuarios.data.map((usuario) => (
                            <tr
                                key={usuario.id}
                                className="border-b hover:bg-gray-50"
                            >
                                <td className="p-3">{usuario.id}</td>
                                <td className="p-3 font-medium">
                                    {usuario.nombre} {usuario.apellido_paterno}{' '}
                                    {usuario.apellido_materno ?? ''}
                                </td>
                                <td className="p-3">{usuario.email}</td>
                                <td className="p-3">{usuario.rol.nombre}</td>
                                <td className="p-3">
                                    <span
                                        className={`rounded px-2 py-1 text-xs font-semibold ${coloresEstado[usuario.estado]}`}
                                    >
                                        {usuario.estado}
                                    </span>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <Paginacion paginador={usuarios} />
            </div>
        </Layout>
    );
}
