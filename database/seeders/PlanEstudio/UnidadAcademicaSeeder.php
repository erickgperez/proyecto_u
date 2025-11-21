<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\PlanEstudio\TipoUnidadAcademica;
use App\Models\PlanEstudio\UnidadAcademica;
use Illuminate\Database\Seeder;

class UnidadAcademicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoId = TipoUnidadAcademica::where('codigo', 'ASIGNATURA')->first()->id;
        UnidadAcademica::createMany([
            //Asignaturas de Tecnologías de la Información
            ['codigo' => 'FTI0101', 'nombre' => 'FUNDAMENTOS DE TECNOLOGÍAS DE LA INFORMACIÓN', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'LDI0101', 'nombre' => 'LÓGICA DIGITAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'IRE0101', 'nombre' => 'INFRAESTRUCTURA DE RED', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SOP0101', 'nombre' => 'SISTEMAS OPERATIVOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PRE0101', 'nombre' => 'PRECÁLCULO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'BDA0101', 'nombre' => 'BASES DE DATOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PRO0101', 'nombre' => 'PROGRAMACIÓN', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'FSE0101', 'nombre' => 'FUNDAMENTOS DE SEGURIDAD', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'ANU0101', 'nombre' => 'ARQUITECTURA DE NUBE', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'RCR0101', 'nombre' => 'REDES CONMUTADAS Y RUTEADAS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'BDN0101', 'nombre' => 'BASES DE DATOS NoSQL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PMO0101', 'nombre' => 'PROGRAMACIÓN PARA MÓVILES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'ISR0101', 'nombre' => 'INFRAESTRUCTURA DE SEGURIDAD DE REDES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'VIR0101', 'nombre' => 'VIRTUALIZACIÓN', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'ICO0101', 'nombre' => 'INTERNET DE LAS COSAS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'ABD0101', 'nombre' => 'ADMINISTRACIÓN DE BASES DE DATOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'DFS0101', 'nombre' => 'DESARROLLO FULL STACK', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'IRI0101', 'nombre' => 'INFRAESTRUCTURA DE REDES INALAMBRICAS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'DRN0101', 'nombre' => 'DESPLIEGUE DE REDES EN LA NUBE', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PIN0101', 'nombre' => 'PROYECTO INTEGRADOR', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            //Asignaturas de Mantenimiento Industrial
            ['codigo' => 'MTA0201', 'nombre' => 'MATEMÁTICA TÉCNICA APLICADA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'MIN0201', 'nombre' => 'MATERIALES DE INGENIERÍA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'IMI0201', 'nombre' => 'INTRODUCCIÓN AL MANTENIMIENTO INDUSTRIAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'MEM0201', 'nombre' => 'MEDICIONES ELÉCTRICAS Y MECÁNICAS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SRM0201', 'nombre' => 'SISTEMAS DE REPRESENTACIÓN Y LECTURA DE MANUALES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'FMC0201', 'nombre' => 'FUNDAMENTOS DE MECÁNICA CLÁSICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'FFT0201', 'nombre' => 'FUNDAMENTOS DE MECÁNICA DE FLUIDOS Y TERMODINÁMICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SHI0201', 'nombre' => 'SEGURIDAD E HIGIENE INDUSTRIAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'EAM0201', 'nombre' => 'ELECTRICIDAD APLICADA AL MANTENIMIENTO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PMH0201', 'nombre' => 'PRÁCTICAS DE MECANIZADO Y HERRAMIENTAS MANUALES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'TLU0201', 'nombre' => 'TÉCNICAS DE LUBRICACIÓN', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'MBC0201', 'nombre' => 'MANTENIMIENTO ELECTROMECÁNICO DE BOMBAS Y COMPRESORES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'MAR0201', 'nombre' => 'MANTENIMIENTO DE SISTEMA DE AIRE ACONDICIONADO Y REFRIGERACIÓN', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'TEM0201', 'nombre' => 'TECNOLOGÍA DE ELEMENTOS MECÁNICOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'TSO0201', 'nombre' => 'TÉCNICAS DE SOLDADURA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'MMT0201', 'nombre' => 'MANTENIMIENTO ELECTROMECÁNICO DE MÁQUINAS TÉRMICAS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'CPM0201', 'nombre' => 'COSTOS DE PLANIFICACIÓN DEL MANTENIMIENTO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'MMA0201', 'nombre' => 'MONTAJE DE MAQUINARIA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'CAR0201', 'nombre' => 'CONTROL AUTOMATIZACIÓN Y ROBÓTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'TMP0201', 'nombre' => 'TÉCNICAS DE MANTENIMIENTO PREDICTIVO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            //Asignaturas de Industrias Alimentarias
            ['codigo' => 'MAP0301', 'nombre' => 'MATEMÁTICA APLICADA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'QGE0301', 'nombre' => 'QUÍMICA GENERAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'BGE0301', 'nombre' => 'BIOLOGÍA GENERAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SHO0301', 'nombre' => 'SEGURIDAD E HIGIENE OCUPACIONAL EN LA INDUSTRIA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'EGA0301', 'nombre' => 'EXPRESIÓN GRÁFICA APLICADA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'FAP0301', 'nombre' => 'FISICA APLICADA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'QOR0301', 'nombre' => 'QUÍMICA ORGANICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'MGA0301', 'nombre' => 'MICROBIOLOGÍA GENERAL Y DE ALIMENTOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PCE0301', 'nombre' => 'TECNOLOGÍA DEL PROCESAMIENTO DE CEREALES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PFH0301', 'nombre' => 'TECNOLOGÍA DEL PROCESAMIENTO DE FRUTAS Y HORTALIZAS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'CCO0301', 'nombre' => 'CONTABILIDAD DE COSTOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'QAN0301', 'nombre' => 'QUÍMICA ANALÍTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'BGA0301', 'nombre' => 'BIOQUÍMICA GENERAL Y DE ALIMENTOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'EAP0301', 'nombre' => 'ESTADÍSTICA APLICADA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PCA0301', 'nombre' => 'TECNOLOGÍA DEL PROCESAMIENTO DE LA CARNE', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'PCP0301', 'nombre' => 'PLANIFICACIÓN Y CONTROL DE LA PRODUCCIÓN', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'AIN0301', 'nombre' => 'ANÁLISIS INSTRUMENTAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'CIA0301', 'nombre' => 'GESTIÓN DE CALIDAD E INOCUIDAD ALIMENTARIA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'DEX0301', 'nombre' => 'DISEÑOS EXPERIMENTALES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PLE0301', 'nombre' => 'TECNOLOGÍA DEL PROCESAMIENTO DE LA LECHE', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            //Asignaturas de Turismo
            ['codigo' => 'TIC0401', 'nombre' => 'TECNOLOGÍAS DE LA INFORMACIÓN Y LAS COMUNICACIONES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'HSE0401', 'nombre' => 'HISTORIA SOCIAL, ECONÓMICA Y DEL TURISMO EN EL SALVADOR', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'GTU0401', 'nombre' => 'GEOGRAFÍA TURÍSTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'TDS0401', 'nombre' => 'TURISMO Y DESARROLLO SOSTENIBLE', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'NNI0401', 'nombre' => 'NORMATIVA TURÍSTICA NACIONAL E INTERNACIONAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'FMF0401', 'nombre' => 'PRECÁLCULO Y FUNDAMENTOS DE MATEMÁTICA FINANCIERA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PNC0401', 'nombre' => 'PATRIMONIO NATURAL Y CULTURAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PES0401', 'nombre' => 'PROBABILIDAD Y ESTADÍSTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'IAL0401', 'nombre' => 'INOCUIDAD ALIMENTARIA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'DTE0401', 'nombre' => 'DESTINOS TURÍSTICOS ESPECIALIZADOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'ADT0401', 'nombre' => 'ANÁLISIS DE LA DEMANDA TURÍSTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'OGA0401', 'nombre' => 'OPERACIÓN GASTRONÓMICA Y DE SERVICIOS COMPLEMENTARIOS AL TURISMO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'GSC0401', 'nombre' => 'GESTIÓN DE SERVICIO AL CLIENTE', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'FEC0401', 'nombre' => 'FUNDAMENTOS DE ECONOMÍA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'OAL0401', 'nombre' => 'OPERACIÓN DEL ALOJAMIENTO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'AOT0401', 'nombre' => 'ANÁLISIS DE LA OFERTA TURÍSTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'MTU0401', 'nombre' => 'MODELO TURÍSTICO INNOVADOR Y PLAN DE NEGOCIO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'IBP0401', 'nombre' => 'IMPLEMENTACIÓN DE BUENAS PRÁCTICAS TURÍSTICAS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'RPT0401', 'nombre' => 'RECURSOS Y PRODUCTOS TURÍSTICOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'OTU0401', 'nombre' => 'OPERADORES TURÍSTICOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            //Asignaturas de Eficiencia Energética
            ['codigo' => 'PRE0501', 'nombre' => 'PRECÁLCULO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SEN0501', 'nombre' => 'SOSTENIBILIDAD ENERGÉTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SOC0501', 'nombre' => 'SEGURIDAD OCUPACIONAL', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'IER0501', 'nombre' => 'INSTALACIONES ELÉCTRICAS Y REDES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PEL0501', 'nombre' => 'PRINCIPIOS DE ELECTRICIDAD', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'PMA0501', 'nombre' => 'PRINCIPIOS DE MANTENIMIENTO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SMT0501', 'nombre' => 'SISTEMAS DE MEDIA TENSIÓN', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'IPD0501', 'nombre' => 'INTERPRETACIÓN DE PLANOS Y DIBUJO CAD', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'ERE0501', 'nombre' => 'ENERGÍAS RENOVABLES', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'IPE0501', 'nombre' => 'INTRODUCCIÓN A LOS PROYECTOS ENERGÉTICOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'SSF0501', 'nombre' => 'SISTEMAS SOLARES FOTOVOLTAICOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SCP0501', 'nombre' => 'SISTEMAS DE CONTROL DE POTENCIA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'MEL0501', 'nombre' => 'MÁQUINAS ELÉCTRICAS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'GDV0501', 'nombre' => 'SISTEMAS DE GENERACIÓN Y DISTRIBUCIÓN DE VAPOR', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PEE0501', 'nombre' => 'PRINCIPIOS DE EFICIENCIA ENERGÉTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

            ['codigo' => 'SHT0501', 'nombre' => 'SISTEMAS HIDRÁULICOS Y TÉRMICOS', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'CLI0501', 'nombre' => 'CLIMATIZACIÓN', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'SAC0501', 'nombre' => 'SISTEMAS DE AIRE COMPRIMIDO', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'CAR0501', 'nombre' => 'CONTROL AUTOMATIZACIÓN Y ROBÓTICA', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],
            ['codigo' => 'PIN0502', 'nombre' => 'PROYECTO INTEGRADOR', 'creditos' => 0, 'tipo_unidad_academica_id' => $tipoId],

        ]);
    }
}
