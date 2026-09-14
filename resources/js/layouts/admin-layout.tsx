import { Link, Head } from '@inertiajs/react';
import {
    Bell,
    Box,
    ClipboardList,
    CreditCard,
    FolderOpen,
    Heart,
    LayoutDashboard,
    LogOut,
    MessageSquare,
    Milestone,
    ScrollText,
    Share2,
    Shield,
    ShoppingCart,
    Tags,
    Users,
} from 'lucide-react';

interface AdminLayoutProps {
    children: React.ReactNode;
}

const modulos = [
    { ruta: '/admin', nombre: 'Dashboard', icono: LayoutDashboard },
    { ruta: '/admin/roles', nombre: 'Roles', icono: Shield },
    { ruta: '/admin/usuarios', nombre: 'Usuarios', icono: Users },
    {
        ruta: '/admin/cuentas-sociales',
        nombre: 'Cuentas Sociales',
        icono: Share2,
    },
    { ruta: '/admin/categorias', nombre: 'Categorías', icono: FolderOpen },
    { ruta: '/admin/etiquetas', nombre: 'Etiquetas', icono: Tags },
    { ruta: '/admin/innovaciones', nombre: 'Innovaciones', icono: Box },
    {
        ruta: '/admin/hitos-financieros',
        nombre: 'Hitos Financieros',
        icono: Milestone,
    },
    { ruta: '/admin/lista-deseos', nombre: 'Lista de Deseos', icono: Heart },
    { ruta: '/admin/carritos', nombre: 'Carritos', icono: ShoppingCart },
    {
        ruta: '/admin/carrito-detalles',
        nombre: 'Detalles de Carrito',
        icono: ClipboardList,
    },
    {
        ruta: '/admin/contribuciones',
        nombre: 'Contribuciones',
        icono: CreditCard,
    },
    { ruta: '/admin/comentarios', nombre: 'Comentarios', icono: MessageSquare },
    { ruta: '/admin/notificaciones', nombre: 'Notificaciones', icono: Bell },
    {
        ruta: '/admin/logs-sistema',
        nombre: 'Logs del Sistema',
        icono: ScrollText,
    },
];

export default function AdminLayout({ children }: AdminLayoutProps) {
    return (
        <>
            <Head title="Panel Administrativo - TheSauce" />
            <div className="flex min-h-screen bg-gray-50">
                {/* Menú Lateral */}
                <aside className="flex w-64 flex-col bg-slate-900 text-white shadow-xl">
                    <div className="border-b border-slate-700 p-6 text-2xl font-bold tracking-tight">
                        TheSauce <span className="text-blue-500">Admin</span>
                    </div>
                    <nav className="flex-1 space-y-2 overflow-y-auto p-4">
                        {modulos.map((modulo) => {
                            const Icono = modulo.icono;

                            return (
                                <Link
                                    key={modulo.ruta}
                                    href={modulo.ruta}
                                    className="flex items-center gap-3 rounded-lg p-3 transition-colors hover:bg-slate-800"
                                >
                                    <Icono size={20} /> {modulo.nombre}
                                </Link>
                            );
                        })}
                    </nav>
                    <div className="border-t border-slate-700 p-4">
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            className="flex w-full items-center gap-3 text-left text-slate-400 transition-colors hover:text-white"
                        >
                            <LogOut size={20} /> Cerrar Sesión
                        </Link>
                    </div>
                </aside>

                {/* Área Principal */}
                <main className="flex flex-1 flex-col">
                    <header className="flex items-center justify-between border-b border-gray-200 bg-white px-8 py-4 shadow-sm">
                        <h1 className="text-xl font-semibold text-gray-800">
                            Panel de Administración
                        </h1>
                        <div className="flex items-center gap-3">
                            <span className="text-sm font-medium text-gray-600">
                                Administrador
                            </span>
                            <div className="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 font-bold text-white shadow-md">
                                A
                            </div>
                        </div>
                    </header>
                    <div className="flex-1 overflow-auto p-8">{children}</div>
                </main>
            </div>
        </>
    );
}
