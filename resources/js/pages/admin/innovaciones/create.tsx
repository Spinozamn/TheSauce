import AdminLayout from '@/layouts/admin-layout';
import { Link } from '@inertiajs/react';

export default function InnovacionesCreate() {
    return (
        <AdminLayout>
            <div className="max-w-3xl rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
                <h2 className="mb-6 text-2xl font-bold text-gray-900">
                    Registrar Nueva Innovación
                </h2>

                <form className="space-y-6">
                    <div>
                        <label className="mb-2 block text-sm font-semibold text-gray-700">
                            Título del Proyecto
                        </label>
                        <input
                            type="text"
                            className="w-full rounded-lg border border-gray-300 p-3 transition-all outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Sistema de riego inteligente con IoT"
                        />
                    </div>

                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label className="mb-2 block text-sm font-semibold text-gray-700">
                                Categoría
                            </label>
                            <select className="w-full rounded-lg border border-gray-300 bg-white p-3 outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">
                                    Selecciona una categoría
                                </option>
                                <option value="agrotech">AgroTech</option>
                                <option value="tecnologia">Tecnología</option>
                                <option value="salud">Salud</option>
                            </select>
                        </div>
                        <div>
                            <label className="mb-2 block text-sm font-semibold text-gray-700">
                                Fecha Límite
                            </label>
                            <input
                                type="date"
                                className="w-full rounded-lg border border-gray-300 p-3 outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label className="mb-2 block text-sm font-semibold text-gray-700">
                            Descripción
                        </label>
                        <textarea
                            rows={4}
                            className="w-full resize-none rounded-lg border border-gray-300 p-3 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Describe el objetivo y funcionamiento de tu innovación..."
                        ></textarea>
                    </div>

                    <div>
                        <label className="mb-2 block text-sm font-semibold text-gray-700">
                            Meta Financiera ($)
                        </label>
                        <input
                            type="number"
                            className="w-full rounded-lg border border-gray-300 p-3 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0.00"
                        />
                    </div>

                    <div className="mt-8 flex gap-4 border-t border-gray-100 pt-6">
                        <Link
                            href="/admin/innovaciones"
                            className="rounded-lg border border-gray-300 px-6 py-3 font-medium text-gray-700 transition-colors hover:bg-gray-50"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="button"
                            className="rounded-lg bg-blue-600 px-6 py-3 font-medium text-white shadow-sm transition-colors hover:bg-blue-700"
                        >
                            Guardar Innovación
                        </button>
                    </div>
                </form>
            </div>
        </AdminLayout>
    );
}
