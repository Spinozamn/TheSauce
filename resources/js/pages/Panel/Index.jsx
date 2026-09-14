import Layout from '@/components/Layout';
import { Link } from '@inertiajs/react';

const modulos = [
    { nombre: 'Roles', ruta: '/roles' },
    { nombre: 'Usuarios', ruta: '/usuarios' },
    { nombre: 'Cuentas Sociales', ruta: '/cuentas-sociales' },
    { nombre: 'Categorías', ruta: '/categorias' },
    { nombre: 'Etiquetas', ruta: '/etiquetas' },
    { nombre: 'Innovaciones', ruta: '/innovaciones' },
    { nombre: 'Hitos Financieros', ruta: '/hitos-financieros' },
    { nombre: 'Lista de Deseos', ruta: '/lista-deseos' },
    { nombre: 'Carritos', ruta: '/carritos' },
    { nombre: 'Detalles de Carrito', ruta: '/carrito-detalles' },
    { nombre: 'Contribuciones', ruta: '/contribuciones' },
    { nombre: 'Comentarios', ruta: '/comentarios' },
    { nombre: 'Notificaciones', ruta: '/notificaciones' },
    { nombre: 'Logs del Sistema', ruta: '/logs-sistema' },
];

export default function Dashboard() {
    return (
        <Layout>
            <div className="rounded-lg bg-white p-6 shadow">
                <h2 className="mb-4 text-2xl font-bold text-gray-800">
                    Bienvenido al Panel de TheSauce
                </h2>
                <p className="mb-6 text-gray-600">
                    Selecciona un módulo para consultar la información
                    almacenada en la base de datos.
                </p>
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {modulos.map((modulo) => (
                        <Link
                            key={modulo.ruta}
                            href={modulo.ruta}
                            className="rounded-lg border border-gray-200 p-4 transition hover:border-blue-500 hover:shadow"
                        >
                            <span className="font-semibold text-gray-800">
                                {modulo.nombre}
                            </span>
                        </Link>
                    ))}
                </div>
            </div>
        </Layout>
    );
}
