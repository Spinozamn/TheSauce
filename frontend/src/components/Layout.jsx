import { Link, Outlet } from 'react-router-dom';
import { LayoutDashboard, Box, Users } from 'lucide-react';

export default function Layout() {
  return (
    <div className="flex min-h-screen bg-gray-100">
      <aside className="w-64 bg-slate-800 text-white flex flex-col">
        <div className="p-4 text-2xl font-bold border-b border-slate-700">TheSauce Admin</div>
        <nav className="flex-1 p-4 space-y-2">
          <Link to="/" className="flex items-center gap-3 p-2 hover:bg-slate-700 rounded transition">
            <LayoutDashboard size={20} /> Dashboard
          </Link>
          <Link to="/innovaciones" className="flex items-center gap-3 p-2 hover:bg-slate-700 rounded transition">
            <Box size={20} /> Innovaciones
          </Link>
          <Link to="/usuarios" className="flex items-center gap-3 p-2 hover:bg-slate-700 rounded transition">
            <Users size={20} /> Usuarios
          </Link>
        </nav>
        <div className="p-4 border-t border-slate-700 text-sm text-slate-400">© 2026 TheSauce</div>
      </aside>
      <main className="flex-1 flex flex-col">
        <header className="bg-white shadow p-4 flex justify-between items-center">
          <h1 className="text-xl font-semibold text-gray-800">Panel de Administración</h1>
          <div className="flex items-center gap-2">
            <span className="text-sm text-gray-600">Administrador</span>
            <div className="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">A</div>
          </div>
        </header>
        <div className="flex-1 p-6 overflow-auto"><Outlet /></div>
      </main>
    </div>
  );
}