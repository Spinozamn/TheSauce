import React, { useState } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';

interface Categoria {
    id: number;
    nombre: string;
}

interface Innovacion {
    id: number;
    categoria_id: number;
    titulo: string;
    descripcion: string;
    meta_financiera: number | string;
    estado: string;
    fecha_inicio: string;
    fecha_fin: string;
    imagen_portada: string | null;
}

interface Props {
    innovacion: Innovacion;
    categorias: Categoria[];
}

export default function Edit({ innovacion, categorias }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        _method: 'put',
        categoria_id: innovacion.categoria_id || '',
        titulo: innovacion.titulo || '',
        descripcion: innovacion.descripcion || '',
        meta_financiera: innovacion.meta_financiera || '',
        estado: innovacion.estado || 'borrador',
        fecha_inicio: innovacion.fecha_inicio ? innovacion.fecha_inicio.substring(0, 10) : '',
        fecha_fin: innovacion.fecha_fin ? innovacion.fecha_fin.substring(0, 10) : '',
        imagen: null as File | null,
    });

    const [previewUrl, setPreviewUrl] = useState<string | null>(innovacion.imagen_portada);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setData('imagen', file);
            setPreviewUrl(URL.createObjectURL(file));
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        // Se envía por POST con _method: 'put' para soporte nativo de multipart/form-data
        post(`/admin/innovaciones/${innovacion.id}`, {
            forceFormData: true,
        });
    };

    return (
        <div className="min-h-screen bg-neutral-950 text-neutral-100 p-8">
            <Head title={`Editar Innovación #${innovacion.id}`} />

            <div className="max-w-4xl mx-auto">
                <div className="flex items-center justify-between mb-8">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">Editar Innovación #{innovacion.id}</h1>
                        <p className="text-sm text-neutral-400 mt-1">
                            Modifica los campos necesarios. La imagen se conservará si no seleccionas una nueva.
                        </p>
                    </div>
                    <Link
                        href="/admin/innovaciones"
                        className="px-4 py-2 rounded-lg border border-neutral-700 text-sm font-medium hover:bg-neutral-800 transition"
                    >
                        Volver al Listado
                    </Link>
                </div>

                <form onSubmit={handleSubmit} className="bg-neutral-900 border border-neutral-800 rounded-xl p-6 space-y-6">
                    {/* Título */}
                    <div>
                        <label className="block text-sm font-medium mb-1">Título de la Innovación</label>
                        <input
                            type="text"
                            value={data.titulo}
                            onChange={(e) => setData('titulo', e.target.value)}
                            className="w-full px-4 py-2 rounded-lg bg-neutral-950 border border-neutral-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                        />
                        {errors.titulo && <p className="text-red-500 text-xs mt-1">{errors.titulo}</p>}
                    </div>

                    {/* Categoría y Estado */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="block text-sm font-medium mb-1">Categoría</label>
                            <select
                                value={data.categoria_id}
                                onChange={(e) => setData('categoria_id', e.target.value)}
                                className="w-full px-4 py-2 rounded-lg bg-neutral-950 border border-neutral-800 focus:border-blue-500 outline-none text-sm text-neutral-200"
                            >
                                <option value="">Selecciona una categoría</option>
                                {categorias.map((cat) => (
                                    <option key={cat.id} value={cat.id}>
                                        {cat.nombre}
                                    </option>
                                ))}
                            </select>
                            {errors.categoria_id && <p className="text-red-500 text-xs mt-1">{errors.categoria_id}</p>}
                        </div>

                        <div>
                            <label className="block text-sm font-medium mb-1">Estado</label>
                            <select
                                value={data.estado}
                                onChange={(e) => setData('estado', e.target.value)}
                                className="w-full px-4 py-2 rounded-lg bg-neutral-950 border border-neutral-800 focus:border-blue-500 outline-none text-sm text-neutral-200"
                            >
                                <option value="borrador">Borrador</option>
                                <option value="activo">Activo</option>
                                <option value="financiado">Financiado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                            {errors.estado && <p className="text-red-500 text-xs mt-1">{errors.estado}</p>}
                        </div>
                    </div>

                    {/* Meta Financiera y Fechas */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label className="block text-sm font-medium mb-1">Meta Financiera ($ MXN)</label>
                            <input
                                type="number"
                                step="0.01"
                                value={data.meta_financiera}
                                onChange={(e) => setData('meta_financiera', e.target.value)}
                                className="w-full px-4 py-2 rounded-lg bg-neutral-950 border border-neutral-800 focus:border-blue-500 outline-none text-sm"
                            />
                            {errors.meta_financiera && <p className="text-red-500 text-xs mt-1">{errors.meta_financiera}</p>}
                        </div>

                        <div>
                            <label className="block text-sm font-medium mb-1">Fecha de Inicio</label>
                            <input
                                type="date"
                                value={data.fecha_inicio}
                                onChange={(e) => setData('fecha_inicio', e.target.value)}
                                className="w-full px-4 py-2 rounded-lg bg-neutral-950 border border-neutral-800 focus:border-blue-500 outline-none text-sm"
                            />
                            {errors.fecha_inicio && <p className="text-red-500 text-xs mt-1">{errors.fecha_inicio}</p>}
                        </div>

                        <div>
                            <label className="block text-sm font-medium mb-1">Fecha de Fin</label>
                            <input
                                type="date"
                                value={data.fecha_fin}
                                onChange={(e) => setData('fecha_fin', e.target.value)}
                                className="w-full px-4 py-2 rounded-lg border border-neutral-800 bg-neutral-950 focus:border-blue-500 outline-none text-sm"
                            />
                            {errors.fecha_fin && <p className="text-red-500 text-xs mt-1">{errors.fecha_fin}</p>}
                        </div>
                    </div>

                    {/* Descripción */}
                    <div>
                        <label className="block text-sm font-medium mb-1">Descripción</label>
                        <textarea
                            rows={4}
                            value={data.descripcion}
                            onChange={(e) => setData('descripcion', e.target.value)}
                            className="w-full px-4 py-2 rounded-lg bg-neutral-950 border border-neutral-800 focus:border-blue-500 outline-none text-sm"
                        />
                        {errors.descripcion && <p className="text-red-500 text-xs mt-1">{errors.descripcion}</p>}
                    </div>

                    {/* Gestión de Imagen */}
                    <div className="border-t border-neutral-800 pt-4">
                        <label className="block text-sm font-medium mb-2">Imagen de Portada</label>
                        <div className="flex items-center gap-6">
                            {previewUrl && (
                                <div className="relative w-32 h-20 rounded-lg overflow-hidden border border-neutral-700 bg-neutral-800">
                                    <img src={previewUrl} alt="Vista previa" className="w-full h-full object-cover" />
                                </div>
                            )}
                            <div>
                                <input
                                    type="file"
                                    accept="image/png, image/jpeg, image/webp"
                                    onChange={handleFileChange}
                                    className="block w-full text-sm text-neutral-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                                />
                                <p className="text-xs text-neutral-500 mt-1">
                                    Formatos permitidos: JPG, PNG o WEBP (máx. 2MB). Si no seleccionas un archivo nuevo, se mantendrá la imagen actual.
                                </p>
                                {errors.imagen && <p className="text-red-500 text-xs mt-1">{errors.imagen}</p>}
                            </div>
                        </div>
                    </div>

                    {/* Botones de acción */}
                    <div className="flex items-center justify-end gap-4 pt-4 border-t border-neutral-800">
                        <Link
                            href="/admin/innovaciones"
                            className="px-4 py-2 rounded-lg text-sm text-neutral-400 hover:text-white transition"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            disabled={processing}
                            className="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-5 py-2 rounded-lg font-medium text-sm transition"
                        >
                            {processing ? 'Actualizando...' : 'Guardar Cambios'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}