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
    carrera: Carrera;
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
    nombre: string;
    cupo: number;
    seleccionados: number;
    seleccionados_publico: number;
    seleccionados_privado: number;
}

interface Semestre {
    id: number;
    descripcion: string;
    codigo: string;
    fecha_inicio: Date;
    fecha_fin: Date;
}

interface UnidadAcademica {
    id: number;
    codigo: string;
    nombre: string;
}

interface CarreraUnidadAcademica {
    id: number;
    semestre: number;
    obligatoria: boolean;
    unidad_academica_id: number;
    unidad_academica: UnidadAcademica;
    carrera: Carrera;
}

interface Imparte {
    id: number;
    carrera_sede_id: number;
    oferta: Oferta;
    cupo: number;
    ofertada: boolean;
}

interface Oferta {
    id: number;
    carrera_unidad_academica: CarreraUnidadAcademica;
    carrera_unidad_academica_id: number;
    matricula: number;
    imparte: Imparte;
}

interface Expediente {
    id: number;
    estado_id: number;
    estado: Estado;
    semestre_id: number;
    semestre: Semestre;
    tipo_curso_id: number;
    tipoCurso: TipoCurso;
    carrera_unidad_academica_id: number;
    carreraUnidadAcademica: CarreraUnidadAcademica;
}

interface Estado {
    id: number;
    codigo: string;
    descripcion: string;
}

interface TipoCurso {
    id: number;
    codigo: string;
    descripcion: string;
}

interface Estudiante {
    id: number;
    persona_id: number;
    persona: Persona;
    carnet: string;
    carrera_sede_id: number;
    carreraSede: CarreraSede;
    expediente: Expediente[];
}
