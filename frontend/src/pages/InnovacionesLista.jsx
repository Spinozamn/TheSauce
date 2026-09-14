import { Link } from 'react-router-dom';

export default function InnovacionesLista() {
  const datos = [
    { id: 1, titulo: 'Sistema de riego inteligente con IoT', categoria: 'AgroTech', estado: 'Activo', meta: 75000 },
    { id: 2, titulo: 'App de reciclaje local comunitario', categoria: 'Tecnología', estado: 'Borrador', meta: 30000 },
  ];

  return (
    <div className="bg-white p-6 rounded-lg shadow">
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-2xl font-bold text-gray-800">Listado de Innovaciones</h2>
        <Link to="/innovaciones/nuevo" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Nueva Innovación</Link>
      </div>
      <table className="w-full text-left border-collapse">
        <thead>
          <tr className="bg-gray-100 border-b">
            <th className="p-3">ID</th><th className="p-3">Título</th><th className="p-3">Categoría</th>
            <th className="p-3">Meta ($)</th><th className="p-3">Estado</th><th className="p-3">Acciones</th>
          </tr>
        </thead>
        <tbody>
          {datos.map((item) => (
            <tr key={item.id} className="border-b hover:bg-gray-50">
              <td className="p-3">{item.id}</td><td className="p-3 font-medium">{item.titulo}</td>
              <td className="p-3">{item.categoria}</td><td className="p-3">${item.meta.toLocaleString()}</td>
              <td className="p-3"><span className="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">{item.estado}</span></td>
              <td className="p-3 flex gap-3">
                <button className="text-blue-600 hover:text-blue-800">Editar</button>
                <button className="text-red-600 hover:text-red-800">Eliminar</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}