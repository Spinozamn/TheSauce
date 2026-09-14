import Layout from '@/components/Layout';
import { Link } from '@inertiajs/react';

export default function Create() {
    return (
        <Layout>
            <div className="max-w-2xl rounded-lg bg-white p-6 shadow">
                <h2 className="mb-6 text-2xl font-bold text-gray-800">
                    Registrar Nueva Innovación
                </h2>

                <form className="space-y-4">
                    <div>
                        <label className="mb-1 block text-sm font-medium text-gray-700">
                            Título del Proyecto
                        </label>
                        <input
                            type="text"
                            className="w-full rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Sistema de riego inteligente"
                        />
                    </div>

                    <div>
                        <label className="mb-1 block text-sm font-medium text-gray-700">
                            Categoría
                        </label>
                        <select className="w-full rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona una categoría</option>
                            <option value="agrotech">AgroTech</option>
                            <option value="tecnologia">Tecnología</option>
                        </select>
                    </div>

                    <div>
                        <label className="mb-1 block text-sm font-medium text-gray-700">
                            Descripción
                        </label>
                        <textarea
                            rows="4"
                            className="w-full rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Describe tu innovación..."
                        ></textarea>
                    </div>

                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label className="mb-1 block text-sm font-medium text-gray-700">
                                Meta Financiera ($)
                            </label>
                            <input
                                type="number"
                                className="w-full rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="0.00"
                            />
                        </div>
                        <div>
                            <label className="mb-1 block text-sm font-medium text-gray-700">
                                Fecha Límite
                            </label>
                            <input
                                type="date"
                                className="w-full rounded border border-gray-300 p-2 outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <div className="mt-6 flex gap-4 border-t pt-4">
                        <Link
                            href="/innovaciones"
                            className="rounded bg-gray-200 px-4 py-2 text-gray-700 transition hover:bg-gray-300"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="button"
                            className="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                        >
                            Guardar Innovación
                        </button>
                    </div>
                </form>
            </div>
        </Layout>
    );
}
