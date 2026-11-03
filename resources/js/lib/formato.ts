export const formatoMoneda = (monto: number | string): string =>
    Number(monto).toLocaleString('es-MX', {
        style: 'currency',
        currency: 'MXN',
    });

export const formatoFecha = (fecha: string | null): string =>
    fecha
        ? new Date(fecha).toLocaleDateString('es-MX', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
          })
        : '—';

export const calcularPorcentaje = (
    recaudado: number | string,
    meta: number | string
): number => {
    const r = Number(recaudado) || 0;
    const m = Number(meta) || 1;
    return Math.round((r / m) * 100);
};

export const calcularDiasRestantes = (fechaFin: string): number => {
    const diff = new Date(fechaFin).getTime() - Date.now();
    return Math.max(0, Math.ceil(diff / (1000 * 60 * 60 * 24)));
};
