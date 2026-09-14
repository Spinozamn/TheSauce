export interface EnlacePagina {
    url: string | null;
    label: string;
    page: number | null;
    active: boolean;
}

export interface Paginador<T> {
    data: T[];
    current_page: number;
    last_page: number;
    from: number | null;
    to: number | null;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
    links: EnlacePagina[];
}

export interface RolResumen {
    id: number;
    nombre: string;
}

export interface UsuarioResumen {
    id: number;
    nombre: string;
    apellido_paterno: string;
    apellido_materno: string | null;
}

export interface InnovacionResumen {
    id: number;
    titulo: string;
}

export interface CategoriaResumen {
    id: number;
    nombre: string;
}

export interface EtiquetaVista {
    id: number;
    nombre: string;
    color: string;
    innovaciones_count?: number;
}

export interface RolVista {
    id: number;
    nombre: string;
    descripcion: string;
}

export interface UsuarioVista {
    id: number;
    nombre: string;
    apellido_paterno: string;
    apellido_materno: string | null;
    email: string;
    telefono: string | null;
    rol: RolResumen;
    estado: string;
}

export interface CuentaSocialVista {
    id: number;
    usuario: UsuarioResumen;
    proveedor: string;
    id_proveedor: string;
    expira_en: string | null;
}

export interface CategoriaVista {
    id: number;
    nombre: string;
    descripcion: string;
    innovaciones_count: number;
    estado: string;
}

export interface InnovacionVista {
    id: number;
    titulo: string;
    usuario: UsuarioResumen;
    categoria: CategoriaResumen;
    etiquetas: EtiquetaVista[];
    meta_financiera: string;
    monto_recaudado: string;
    estado: string;
}

export interface HitoFinancieroVista {
    id: number;
    innovacion: InnovacionResumen;
    titulo: string;
    monto_objetivo: string;
    fecha_limite: string;
    estado: string;
}

export interface ListaDeseoVista {
    id: number;
    usuario: UsuarioResumen;
    innovacion: InnovacionResumen;
    created_at: string;
}

export interface CarritoVista {
    id: number;
    usuario: UsuarioResumen;
    estado: string;
    detalles_count: number;
    created_at: string;
}

export interface CarritoDetalleVista {
    id: number;
    carrito: {
        usuario: UsuarioResumen;
    };
    innovacion: InnovacionResumen;
    monto_comprometido: string;
    mensaje_apoyo: string | null;
    recompensa_seleccionada: string;
}

export interface ContribucionVista {
    id: number;
    usuario: UsuarioResumen;
    innovacion: InnovacionResumen;
    monto: string;
    metodo_pago: string;
    estado: string;
    fecha_contribucion: string;
}

export interface ComentarioVista {
    id: number;
    usuario: UsuarioResumen;
    innovacion: InnovacionResumen;
    padre: {
        id: number;
        contenido: string;
    } | null;
    contenido: string;
    fecha_comentario: string;
}

export interface NotificacionVista {
    id: number;
    usuario: UsuarioResumen;
    titulo: string;
    tipo: string;
    leido: boolean;
    fecha_notificacion: string;
}

export interface LogSistemaVista {
    id: number;
    usuario: UsuarioResumen | null;
    accion: string;
    entidad_afectada: string | null;
    ip_origen: string;
    fecha_registro: string;
}
