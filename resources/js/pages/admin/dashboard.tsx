import AdminLayout from '@/layouts/admin-layout';

export default function Dashboard() {
    return (
        <AdminLayout>
            <div className="rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
                <h2 className="mb-4 text-3xl font-bold text-gray-900">
                    Bienvenido al Panel de TheSauce
                </h2>
                <p className="text-lg text-gray-600">
                    Selecciona una opción del menú lateral para gestionar la
                    plataforma de crowdfunding y supervisar las innovaciones
                    locales.
                </p>
            </div>
        </AdminLayout>
    );
}
