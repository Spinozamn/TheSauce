import AdminLayout from '@/layouts/admin-layout';

export default function Dashboard() {
    return (
        <AdminLayout>
            <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <h2 className="text-3xl font-bold mb-4 text-gray-900">Bienvenido al Panel de TheSauce</h2>
                <p className="text-gray-600 text-lg">
                    Selecciona una opción del menú lateral para gestionar la plataforma de crowdfunding 
                    y supervisar las innovaciones locales.
                </p>
            </div>
        </AdminLayout>
    );
}