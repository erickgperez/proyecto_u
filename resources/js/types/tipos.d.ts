export interface Header {
    title: string;
    key: string;
}

export interface SortBy {
    order: boolean | 'asc' | 'desc' | undefined;
    key: string;
}

export interface Grado {
    id: number | null;
    descripcion_masculino: string;
    descripcion_femenino: string;
}

export interface Distrito {
    id: number | null;
    descripcion: string;
    municipio_id: number | null;
}

export interface Departamento {
    id: number | null;
    descripcion: string;
}

export interface Municipio {
    id: number | null;
    descripcion: string;
    departamento_id: number | null;
}

export interface Sede {
    id: number | null;
    codigo: string;
    nombre: string;
}

export interface TipoCarrera {
    id: number | null;
    codigo: string;
    descripcion: string;
}

export interface Carrera {
    id: number | null;
    codigo: string;
    nombre: string;
    nombreCompleto: string;
}

export interface Etapa {
    id: number | null;
    codigo: string;
    nombre: string;
    indicaciones: string;
}
