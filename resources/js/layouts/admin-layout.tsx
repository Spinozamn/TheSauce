import { Link, Head } from '@inertiajs/react';
import { LayoutDashboard, Box, Users, LogOut } from 'lucide-react';

interface AdminLayoutProps {
    children: React.ReactNode;
}

export default function AdminLayout({ children }: AdminLayoutProps) {
    return (
        <>
            <Head title="Panel Administrativo - TheSauce" />
            <div className="flex min-h-screen bg-gray-50">
                {/* Menú Lateral */}
                <aside className="w-64 bg-slate-900 text-white flex flex-col shadow-xl">
                    <div className="p-6 text-2xl font-bold border-b border-slate-700 tracking-tight">
                        TheSauce <span className="text-blue-500">Admin</span>
                    </div>
                    <nav className="flex-1 p-4 space-y-2">
                        <Link 
                            href="/admin" 
                            className="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition-colors"
                        >
                            <LayoutDashboard size={20} /> Dashboard
                        </Link>
                        <Link 
                            href="/admin/innovaciones" 
                            className="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition-colors"
                        >
                            <Box size={20} /> Innovaciones
                        </Link>
                        <Link 
                            href="/admin/usuarios" 
                            className="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition-colors"
                        >
                            <Users size={20} /> Usuarios
                        </Link>
                    </nav>
                    <div className="p-4 border-t border-slate-700">
                        <Link href="/logout" method="post" as="button" className="flex items-center gap-3 text-slate-400 hover:text-white transition-colors w-full text-left">
                            <LogOut size={20} /> Cerrar Sesión
                        </Link>
                    </div>
                </aside>

                {/* Área Principal */}
                <main className="flex-1 flex flex-col">
                    <header className="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center shadow-sm">
                        <h1 className="text-xl font-semibold text-gray-800">Panel de Administración</h1>
                        <div className="flex items-center gap-3">
                            <span className="text-sm text-gray-600 font-medium">Administrador</span>
                            <div className="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                A
                            </div>
                        </div>
                    </header>
                    <div className="flex-1 p-8 overflow-auto">
                        {children}
                    </div>
                </main>
            </div>
        </>
    );
}