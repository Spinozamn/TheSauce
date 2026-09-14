import { Link } from 'react-router-dom';

export default function InnovacionesFormulario() {
  return (
    <div className="bg-white p-6 rounded-lg shadow max-w-2xl">
      <h2 className="text-2xl font-bold text-gray-800 mb-6">Registrar Nueva Innovación</h2>
      <form className="space-y-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Título del Proyecto</label>
          <input type="text" className="w-full border border-gray-300 rounded p-2" placeholder="Ej: Sistema de riego inteligente" />
        </div>
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
          <select className="w-full border border-gray-300 rounded p-2">
            <option value="">Selecciona una categoría</option>
            <option value="agrotech">AgroTech</option>
            <option value="tecnologia">Tecnología</option>
          </select>
        </div>
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
          <textarea rows="4" className="w-full border border-gray-300 rounded p-2" placeholder="Describe tu innovación..."></textarea>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Meta Financiera ($)</label>
            <input type="number" className="w-full border border-gray-300 rounded p-2" placeholder="0.00" />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Fecha Límite</label>
            <input type="date" className="w-full border border-gray-300 rounded p-2" />
          </div>
        </div>
        <div className="flex gap-4 pt-4 border-t mt-6">
          <Link to="/innovaciones" className="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Cancelar</Link>
          <button type="button" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar Innovación</button>
        </div>
      </form>
    </div>
  );
}