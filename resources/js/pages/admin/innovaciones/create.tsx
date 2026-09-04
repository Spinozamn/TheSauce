import AdminLayout from '@/layouts/admin-layout';
import { Link } from '@inertiajs/react';

export default function InnovacionesCreate() {
    return (
        <AdminLayout>
            <div className="max-w-3xl bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h2 className="text-2xl font-bold text-gray-900 mb-6">Registrar Nueva Innovación</h2>
                
                <form className="space-y-6">
                    <div>
                        <label className="block text-sm font-semibold text-gray-700 mb-2">Título del Proyecto</label>
                        <input 
                            type="text" 
                            className="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" 
                            placeholder="Ej: Sistema de riego inteligente con IoT" 
                        />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-2">Categoría</label>
                            <select className="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <option value="">Selecciona una categoría</option>
                                <option value="agrotech">AgroTech</option>
                                <option value="tecnologia">Tecnología</option>
                                <option value="salud">Salud</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-semibold text-gray-700 mb-2">Fecha Límite</label>
                            <input 
                                type="date" 
                                className="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none" 
                            />
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                        <textarea 
                            rows={4} 
                            className="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none resize-none" 
                            placeholder="Describe el objetivo y funcionamiento de tu innovación..."
                        ></textarea>
                    </div>

                    <div>
                        <label className="block text-sm font-semibold text-gray-700 mb-2">Meta Financiera ($)</label>
                        <input 
                            type="number" 
                            className="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 outline-none" 
                            placeholder="0.00" 
                        />
                    </div>

                    <div className="flex gap-4 pt-6 border-t border-gray-100 mt-8">
                        <Link 
                            href="/admin/innovaciones" 
                            className="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button 
                            type="button" 
                            className="px-6 py-3 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors shadow-sm"
                        >
                            Guardar Innovación
                        </button>
                    </div>
                </form>
            </div>
        </AdminLayout>
    );
}