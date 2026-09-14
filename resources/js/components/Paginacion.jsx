import { Link } from '@inertiajs/react';

export default function Paginacion({ paginador }) {
    const paginas = paginador.links.filter((enlace) => enlace.page !== null);

    return (
        <div className="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
            <p className="text-sm text-gray-600">
                Mostrando {paginador.from ?? 0} a {paginador.to ?? 0} de{' '}
                {paginador.total} registros
            </p>
            {paginador.last_page > 1 && (
                <div className="flex items-center gap-1">
                    {paginador.prev_page_url ? (
                        <Link
                            href={paginador.prev_page_url}
                            className="rounded border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 transition hover:bg-gray-100"
                        >
                            Anterior
                        </Link>
                    ) : (
                        <span className="cursor-not-allowed rounded border border-gray-300 bg-gray-100 px-3 py-1.5 text-sm text-gray-400">
                            Anterior
                        </span>
                    )}
                    {paginas.map((enlace) => (
                        <Link
                            key={enlace.page}
                            href={enlace.url}
                            className={`rounded border px-3 py-1.5 text-sm transition ${
                                enlace.active
                                    ? 'border-blue-600 bg-blue-600 text-white'
                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-100'
                            }`}
                        >
                            {enlace.page}
                        </Link>
                    ))}
                    {paginador.next_page_url ? (
                        <Link
                            href={paginador.next_page_url}
                            className="rounded border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 transition hover:bg-gray-100"
                        >
                            Siguiente
                        </Link>
                    ) : (
                        <span className="cursor-not-allowed rounded border border-gray-300 bg-gray-100 px-3 py-1.5 text-sm text-gray-400">
                            Siguiente
                        </span>
                    )}
                </div>
            )}
        </div>
    );
}
