import { Head, Link } from '@inertiajs/react';
import {
    Bell,
    Box,
    ClipboardList,
    CreditCard,
    FolderOpen,
    Heart,
    LayoutDashboard,
    MessageSquare,
    Milestone,
    ScrollText,
    Share2,
    Shield,
    ShoppingCart,
    Tags,
    Users,
} from 'lucide-react';

const modulos = [
    { ruta: '/panel', nombre: 'Dashboard', icono: LayoutDashboard },
    { ruta: '/roles', nombre: 'Roles', icono: Shield },
    { ruta: '/usuarios', nombre: 'Usuarios', icono: Users },
    { ruta: '/cuentas-sociales', nombre: 'Cuentas Sociales', icono: Share2 },
    { ruta: '/categorias', nombre: 'Categorías', icono: FolderOpen },
    { ruta: '/etiquetas', nombre: 'Etiquetas', icono: Tags },
    { ruta: '/innovaciones', nombre: 'Innovaciones', icono: Box },
    {
        ruta: '/hitos-financieros',
        nombre: 'Hitos Financieros',
        icono: Milestone,
    },
    { ruta: '/lista-deseos', nombre: 'Lista de Deseos', icono: Heart },
    { ruta: '/carritos', nombre: 'Carritos', icono: ShoppingCart },
    {
        ruta: '/carrito-detalles',
        nombre: 'Detalles de Carrito',
        icono: ClipboardList,
    },
    { ruta: '/contribuciones', nombre: 'Contribuciones', icono: CreditCard },
    { ruta: '/comentarios', nombre: 'Comentarios', icono: MessageSquare },
    { ruta: '/notificaciones', nombre: 'Notificaciones', icono: Bell },
    { ruta: '/logs-sistema', nombre: 'Logs del Sistema', icono: ScrollText },
];

export default function Layout({ children }) {
    return (
        <>
            <Head title="TheSauce Admin" />
            <div className="flex min-h-screen bg-gray-100">
                <aside className="flex w-64 flex-col bg-slate-800 text-white">
                    <div className="border-b border-slate-700 p-4 text-2xl font-bold">
                        TheSauce Admin
                    </div>
                    <nav className="flex-1 space-y-2 overflow-y-auto p-4">
                        {modulos.map((modulo) => {
                            const Icono = modulo.icono;

                            return (
                                <Link
                                    key={modulo.ruta}
                                    href={modulo.ruta}
                                    className="flex items-center gap-3 rounded p-2 transition hover:bg-slate-700"
                                >
                                    <Icono size={20} />
                                    {modulo.nombre}
                                </Link>
                            );
                        })}
                    </nav>
                    <div className="border-t border-slate-700 p-4 text-sm text-slate-400">
                        © 2026 TheSauce
                    </div>
                </aside>
                <main className="flex flex-1 flex-col">
                    <header className="flex items-center justify-between bg-white p-4 shadow">
                        <h1 className="text-xl font-semibold text-gray-800">
                            Panel de Administración
                        </h1>
                        <div className="flex items-center gap-2">
                            <span className="text-sm text-gray-600">
                                Administrador
                            </span>
                            <div className="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 font-bold text-white">
                                A
                            </div>
                        </div>
                    </header>
                    <div className="flex-1 overflow-auto p-6">{children}</div>
                </main>
            </div>
        </>
    );
}
