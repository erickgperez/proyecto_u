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

interface Opcion {
    carrera_sede_id: number;
    carrera: string;
    opcion: string;
}

interface Solicitud {
    id: number;
    nie: string;
    nombre: string;
    sector: string;
    nota: number;
    seleccionado: boolean;
    carrera_nombre: string;
    carrera_codigo: string;
    sexo: string;
    carrera_sede_id: number | null;
    solicitud_carrera_sede_id: number | null;
    PRIMERA_OPCION: Opcion | null;
    SEGUNDA_OPCION: Opcion | null;
    TERCERA_OPCION: Opcion | null;
}

interface CarreraSede {
    id: number;
    sede: Sede;
    carrera: string;
    carrera_tipo: string;
    carrera_nombre: string;
    seleccionados: number;
    cupo: number;
    seleccionados_publico: number;
    seleccionados_privado: number;
}

interface Configuracion {
    id: number;
    fecha_publicacion_resultados: Date | null;
    cuota_sector_publico: number | null;
}

interface Convocatoria {
    id: number;
    nombre: string;
    descripcion: string;
    carreras_sedes: [];
    configuracion: Configuracion | null;
}

interface InfoSede {
    cupoSede: number;
    seleccionadosSede: number;
    seleccionadosPublicoSede: number;
    seleccionadosPrivadoSede: number;
}
