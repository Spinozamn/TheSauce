import React, { useState } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import AdminLayout from '@/layouts/admin-layout';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Categoria {
    id: number;
    nombre: string;
}

interface Props {
    categorias: Categoria[];
}

export default function CreateInnovacion({ categorias }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        titulo: '',
        categoria_id: '',
        descripcion: '',
        meta_financiera: '',
        fecha_inicio: '',
        fecha_fin: '',
        imagen: null as File | null,
    });

    const [clientErrors, setClientErrors] = useState<Record<string, string>>({});
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);

    // Validación de imagen en cliente (tipo y tamaño)
    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        const maxSizeBytes = 2 * 1024 * 1024; // 2MB

        if (!allowedTypes.includes(file.type)) {
            setClientErrors(prev => ({ ...prev, imagen: 'Solo se permiten imágenes JPG, PNG o WEBP.' }));
            return;
        }

        if (file.size > maxSizeBytes) {
            setClientErrors(prev => ({ ...prev, imagen: 'La imagen excede el límite máximo de 2MB.' }));
            return;
        }

        setClientErrors(prev => {
            const next = { ...prev };
            delete next.imagen;
            return next;
        });

        setData('imagen', file);
        setPreviewUrl(URL.createObjectURL(file));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Validaciones en interfaz antes del envío
        const errs: Record<string, string> = {};

        if (!data.titulo.trim() || data.titulo.length < 5) {
            errs.titulo = 'El título debe tener al menos 5 caracteres.';
        }
        if (!data.categoria_id) {
            errs.categoria_id = 'Selecciona una categoría obligatoria.';
        }
        if (!data.descripcion.trim() || data.descripcion.length < 20) {
            errs.descripcion = 'La descripción debe contener mínimo 20 caracteres.';
        }
        if (!data.meta_financiera || Number(data.meta_financiera) < 1000) {
            errs.meta_financiera = 'La meta mínima permitida es de $1,000 MXN.';
        }
        if (!data.fecha_inicio) {
            errs.fecha_inicio = 'Ingresa una fecha de inicio válida.';
        }
        if (!data.fecha_fin) {
            errs.fecha_fin = 'Ingresa una fecha límite válida.';
        } else if (data.fecha_inicio && data.fecha_fin <= data.fecha_inicio) {
            errs.fecha_fin = 'La fecha límite debe ser posterior a la fecha de inicio.';
        }
        if (!data.imagen) {
            errs.imagen = 'Debes adjuntar una imagen de portada.';
        }

        setClientErrors(errs);

        if (Object.keys(errs).length > 0) {
            return; // Bloquea la petición si hay errores en el formulario
        }

        // Envío con multipart/form-data automático por Inertia
        post('/admin/innovaciones', {
            forceFormData: true,
        });
    };

    return (
        <AdminLayout>
            <Head title="Registrar Nueva Innovación" />

            <div className="max-w-4xl mx-auto p-6 bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 shadow-sm">
                <div className="mb-6 border-b pb-4">
                    <h1 className="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        Nueva Innovación
                    </h1>
                    <p className="text-sm text-neutral-500">
                        Completa los campos para publicar un nuevo proyecto en la plataforma.
                    </p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Título */}
                    <div>
                        <Label htmlFor="titulo">Título del Proyecto *</Label>
                        <Input
                            id="titulo"
                            type="text"
                            placeholder="Ej. Sistema de riego automatizado con sensores"
                            value={data.titulo}
                            onChange={e => setData('titulo', e.target.value)}
                            className="mt-1"
                        />
                        <InputError message={clientErrors.titulo || errors.titulo} />
                    </div>

                    {/* Categoría */}
                    <div>
                        <Label htmlFor="categoria_id">Categoría *</Label>
                        <select
                            id="categoria_id"
                            value={data.categoria_id}
                            onChange={e => setData('categoria_id', e.target.value)}
                            className="mt-1 flex h-9 w-full rounded-md border border-neutral-300 bg-transparent px-3 py-1 text-sm shadow-xs focus:ring-2 focus:ring-neutral-950 dark:border-neutral-700"
                        >
                            <option value="">-- Selecciona una categoría --</option>
                            {categorias.map(cat => (
                                <option key={cat.id} value={cat.id}>
                                    {cat.nombre}
                                </option>
                            ))}
                        </select>
                        <InputError message={clientErrors.categoria_id || errors.categoria_id} />
                    </div>

                    {/* Descripción */}
                    <div>
                        <Label htmlFor="descripcion">Descripción detallada *</Label>
                        <textarea
                            id="descripcion"
                            rows={4}
                            placeholder="Explica el impacto técnico y comercial de la innovación..."
                            value={data.descripcion}
                            onChange={e => setData('descripcion', e.target.value)}
                            className="mt-1 flex w-full rounded-md border border-neutral-300 bg-transparent p-3 text-sm shadow-xs focus:ring-2 focus:ring-neutral-950 dark:border-neutral-700"
                        />
                        <InputError message={clientErrors.descripcion || errors.descripcion} />
                    </div>

                    {/* Meta Financiera y Fechas */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <Label htmlFor="meta_financiera">Meta Financiera ($ MXN) *</Label>
                            <Input
                                id="meta_financiera"
                                type="number"
                                step="0.01"
                                placeholder="50000.00"
                                value={data.meta_financiera}
                                onChange={e => setData('meta_financiera', e.target.value)}
                                className="mt-1"
                            />
                            <InputError message={clientErrors.meta_financiera || errors.meta_financiera} />
                        </div>

                        <div>
                            <Label htmlFor="fecha_inicio">Fecha de Inicio *</Label>
                            <Input
                                id="fecha_inicio"
                                type="date"
                                value={data.fecha_inicio}
                                onChange={e => setData('fecha_inicio', e.target.value)}
                                className="mt-1"
                            />
                            <InputError message={clientErrors.fecha_inicio || errors.fecha_inicio} />
                        </div>

                        <div>
                            <Label htmlFor="fecha_fin">Fecha Límite *</Label>
                            <Input
                                id="fecha_fin"
                                type="date"
                                value={data.fecha_fin}
                                onChange={e => setData('fecha_fin', e.target.value)}
                                className="mt-1"
                            />
                            <InputError message={clientErrors.fecha_fin || errors.fecha_fin} />
                        </div>
                    </div>

                    {/* Carga de Imagen de Portada */}
                    <div>
                        <Label htmlFor="imagen">Imagen de Portada (JPG, PNG, WEBP - Máx 2MB) *</Label>
                        <Input
                            id="imagen"
                            type="file"
                            accept="image/png, image/jpeg, image/webp"
                            onChange={handleImageChange}
                            className="mt-1"
                        />
                        <InputError message={clientErrors.imagen || errors.imagen} />

                        {previewUrl && (
                            <div className="mt-3">
                                <span className="text-xs text-neutral-500 block mb-1">Vista previa:</span>
                                <img
                                    src={previewUrl}
                                    alt="Vista previa"
                                    className="h-40 w-auto object-cover rounded-lg border border-neutral-200"
                                />
                            </div>
                        )}
                    </div>

                    {/* Botones de Acción */}
                    <div className="flex justify-end gap-3 pt-4 border-t">
                        <Button variant="outline" asChild>
                            <Link href="/admin/innovaciones">Cancelar</Link>
                        </Button>
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Guardando...' : 'Registrar Innovación'}
                        </Button>
                    </div>
                </form>
            </div>
        </AdminLayout>
    );
}