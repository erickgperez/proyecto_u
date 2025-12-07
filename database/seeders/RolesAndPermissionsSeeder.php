<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        // ************** MODULOS
        Permission::create(['name' => 'MODULO_INGRESO']);
        Permission::create(['name' => 'MODULO_CALIFICACIONES']);
        Permission::create(['name' => 'MODULO_GESTION-ACADEMICA']);
        Permission::create(['name' => 'MODULO_ADMINISTRACION']);

        //************ MENUS
        //*****  ADMINISTRACION
        Permission::create(['name' => 'MENU_ADMINISTRACION_SEGURIDAD']);

        //Permisos
        Permission::create(['name' => 'MENU_ADMINISTRACION_SEGURIDAD_PERMISOS']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_PERMISOS_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_PERMISOS_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_PERMISOS_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_PERMISOS_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_PERMISOS_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_PERMISOS_BORRAR']);

        // TIPO CALENDARIZACION
        Permission::create(['name' => 'MENU_ADMINISTRACION_CALENDARIZACION_TIPO']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO_BORRAR']);

        // TIPO EVENTO
        Permission::create(['name' => 'MENU_ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_BORRAR']);

        //Roles
        Permission::create(['name' => 'MENU_ADMINISTRACION_SEGURIDAD_ROLES']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_ROLES_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_ROLES_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_ROLES_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_ROLES_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_ROLES_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_ROLES_BORRAR']);

        //Usuario
        Permission::create(['name' => 'MENU_ADMINISTRACION_SEGURIDAD_USUARIO']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_USUARIO_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_USUARIO_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_USUARIO_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_USUARIO_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_USUARIO_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_SEGURIDAD_USUARIO_BORRAR']);

        // PERSONA
        Permission::create(['name' => 'MENU_ADMINISTRACION_PERFIL']);
        Permission::create(['name' => 'MENU_ADMINISTRACION_PERFIL_ASPIRANTE']);
        Permission::create(['name' => 'MENU_ADMINISTRACION_PERFIL_ESTUDIANTE']);
        Permission::create(['name' => 'MENU_ADMINISTRACION_PERFIL_DOCENTE']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_BORRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_DATOS-CONTACTO']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_DOCUMENTOS']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_DOCUMENTOS_AGREGAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_DOCUMENTOS_BORRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_DOCENTE_ASIGNACION-CARRERA-SEDE']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_DOCENTE_CARGA-ACADEMICA']);

        Permission::create(['name' => 'ADMINISTRACION_PERFIL_AUTORIZAR_EDICION_DATOS-PERSONALES']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_AUTORIZAR_EDICION_DATOS-CONTACTO']);
        Permission::create(['name' => 'ADMINISTRACION_PERFIL_AUTORIZAR_EDICION_DOCUMENTOS']);

        // TIPO_DOCUMENTO
        Permission::create(['name' => 'MENU_ADMINISTRACION_TIPO-DOCUMENTO']);
        Permission::create(['name' => 'ADMINISTRACION_TIPO-DOCUMENTO_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_TIPO-DOCUMENTO_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_TIPO-DOCUMENTO_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_TIPO-DOCUMENTO_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_TIPO-DOCUMENTO_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_TIPO-DOCUMENTO_BORRAR']);

        Permission::create(['name' => 'MENU_ADMINISTRACION_PROCESOS']);

        //Estado
        Permission::create(['name' => 'MENU_ADMINISTRACION_PROCESOS_ESTADO']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ESTADO_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ESTADO_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ESTADO_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ESTADO_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ESTADO_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ESTADO_BORRAR']);

        //Tipo flujo
        Permission::create(['name' => 'MENU_ADMINISTRACION_PROCESOS_TIPO-PROCESO']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TIPO-PROCESO_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TIPO-PROCESO_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TIPO-PROCESO_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TIPO-PROCESO_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TIPO-PROCESO_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TIPO-PROCESO_BORRAR']);

        //Flujo
        Permission::create(['name' => 'MENU_ADMINISTRACION_PROCESOS_PROCESO']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_PROCESO_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_PROCESO_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_PROCESO_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_PROCESO_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_PROCESO_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_PROCESO_BORRAR']);

        //etapa
        Permission::create(['name' => 'MENU_ADMINISTRACION_PROCESOS_ETAPA']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ETAPA_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ETAPA_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ETAPA_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ETAPA_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ETAPA_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_ETAPA_BORRAR']);

        //transicion
        Permission::create(['name' => 'MENU_ADMINISTRACION_PROCESOS_TRANSICION']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TRANSICION_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TRANSICION_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TRANSICION_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TRANSICION_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TRANSICION_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PROCESOS_TRANSICION_BORRAR']);


        //*****  INGRESO
        Permission::create(['name' => 'MENU_INGRESO_CONVOCATORIA']);

        // CONVOCATORIA_GESTIONAR
        Permission::create(['name' => 'MENU_INGRESO_CONVOCATORIA_GESTIONAR-CONVOCATORIA']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_TODO']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_CREAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_EXPORTAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_EDITAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_MOSTRAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_BORRAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_CALENDARIZAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_OFERTA']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_CONFIGURACION']);

        Permission::create(['name' => 'MENU_INGRESO_CONVOCATORIA_CARGAR-ARCHIVO']);
        // CANDIDATOS
        Permission::create(['name' => 'MENU_INGRESO_CONVOCATORIA_CANDIDATOS']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_CANDIDATOS_LISTADO']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_CANDIDATOS_INVITACIONES']);

        Permission::create(['name' => 'MENU_INGRESO_ASPIRANTES']);
        Permission::create(['name' => 'MENU_INGRESO_ASPIRANTES_SELECCION']);
        Permission::create(['name' => 'MENU_INGRESO_ASPIRANTES_SELECCION_NOTIFICAR']);
        Permission::create(['name' => 'MENU_INGRESO_ASPIRANTES_CALIFICACION-BACHILLERATO']);
        Permission::create(['name' => 'MENU_INGRESO_ASPIRANTES_PERFIL']);

        Permission::create(['name' => 'MENU_INGRESO_REPORTES']);
        Permission::create(['name' => 'MENU_INGRESO_REPORTES_ASPIRANTES']);

        //***** GESTION ACADEMICA
        // SEDES
        Permission::create(['name' => 'MENU_ACADEMICO_SEDE']);
        Permission::create(['name' => 'ACADEMICO_SEDE_TODO']);
        Permission::create(['name' => 'ACADEMICO_SEDE_CREAR']);
        Permission::create(['name' => 'ACADEMICO_SEDE_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_SEDE_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_SEDE_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_SEDE_BORRAR']);

        // SEMESTRE
        Permission::create(['name' => 'MENU_ACADEMICO_SEMESTRE']);
        Permission::create(['name' => 'ACADEMICO_SEMESTRE_TODO']);
        Permission::create(['name' => 'ACADEMICO_SEMESTRE_CREAR']);
        Permission::create(['name' => 'ACADEMICO_SEMESTRE_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_SEMESTRE_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_SEMESTRE_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_SEMESTRE_BORRAR']);
        Permission::create(['name' => 'ACADEMICO_SEMESTRE_OFERTA']);
        Permission::create(['name' => 'ACADEMICO_SEMESTRE_CALENDARIZAR']);

        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO']);

        // GRADO
        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO_GRADO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_GRADO_TODO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_GRADO_CREAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_GRADO_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_GRADO_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_GRADO_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_GRADO_BORRAR']);

        // TIPO_CARRERA
        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA_TODO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA_CREAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA_BORRAR']);

        // CARRERA
        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO_CARRERA']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_CARRERA_TODO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_CARRERA_CREAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_CARRERA_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_CARRERA_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_CARRERA_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_CARRERA_BORRAR']);

        //AREA
        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO_AREA']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_AREA_TODO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_AREA_CREAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_AREA_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_AREA_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_AREA_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_AREA_BORRAR']);

        //TIPO UNIDAD ACADEMICA
        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO_TIPO-UNIDAD-ACADEMICA']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-UNIDAD-ACADEMICA_TODO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-UNIDAD-ACADEMICA_CREAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-UNIDAD-ACADEMICA_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-UNIDAD-ACADEMICA_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-UNIDAD-ACADEMICA_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-UNIDAD-ACADEMICA_BORRAR']);

        //UNIDAD ACADEMICA
        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO_UNIDAD-ACADEMICA']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_UNIDAD-ACADEMICA_TODO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_UNIDAD-ACADEMICA_CREAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_UNIDAD-ACADEMICA_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_UNIDAD-ACADEMICA_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_UNIDAD-ACADEMICA_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_UNIDAD-ACADEMICA_BORRAR']);

        // MALLA CURRICULAR
        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_TODO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_CREAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_BORRAR']);

        //TIPO REQUISITO
        Permission::create(['name' => 'MENU_ACADEMICO_PLAN-ESTUDIO_TIPO-REQUISITO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-REQUISITO_TODO']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-REQUISITO_CREAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-REQUISITO_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-REQUISITO_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-REQUISITO_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_PLAN-ESTUDIO_TIPO-REQUISITO_BORRAR']);

        //TIPO CURSO
        Permission::create(['name' => 'MENU_ACADEMICO_TIPO-CURSO']);
        Permission::create(['name' => 'ACADEMICO_TIPO-CURSO_TODO']);
        Permission::create(['name' => 'ACADEMICO_TIPO-CURSO_CREAR']);
        Permission::create(['name' => 'ACADEMICO_TIPO-CURSO_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_TIPO-CURSO_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_TIPO-CURSO_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_TIPO-CURSO_BORRAR']);


        //USO ESTADO
        Permission::create(['name' => 'MENU_ACADEMICO_USO-ESTADO']);
        Permission::create(['name' => 'ACADEMICO_USO-ESTADO_TODO']);
        Permission::create(['name' => 'ACADEMICO_USO-ESTADO_CREAR']);
        Permission::create(['name' => 'ACADEMICO_USO-ESTADO_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_USO-ESTADO_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_USO-ESTADO_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_USO-ESTADO_BORRAR']);

        //ESTADO
        Permission::create(['name' => 'MENU_ACADEMICO_ESTADO']);
        Permission::create(['name' => 'ACADEMICO_ESTADO_TODO']);
        Permission::create(['name' => 'ACADEMICO_ESTADO_CREAR']);
        Permission::create(['name' => 'ACADEMICO_ESTADO_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_ESTADO_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_ESTADO_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_ESTADO_BORRAR']);

        //FORMA IMPARTE
        Permission::create(['name' => 'MENU_ACADEMICO_FORMA-IMPARTE']);
        Permission::create(['name' => 'ACADEMICO_FORMA-IMPARTE_TODO']);
        Permission::create(['name' => 'ACADEMICO_FORMA-IMPARTE_CREAR']);
        Permission::create(['name' => 'ACADEMICO_FORMA-IMPARTE_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_FORMA-IMPARTE_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_FORMA-IMPARTE_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_FORMA-IMPARTE_BORRAR']);

        //EVALUACION
        Permission::create(['name' => 'MENU_ACADEMICO_EVALUACION']);
        Permission::create(['name' => 'ACADEMICO_EVALUACION_TODO']);
        Permission::create(['name' => 'ACADEMICO_EVALUACION_CREAR']);
        Permission::create(['name' => 'ACADEMICO_EVALUACION_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICO_EVALUACION_EDITAR']);
        Permission::create(['name' => 'ACADEMICO_EVALUACION_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICO_EVALUACION_BORRAR']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // Crear roles y asignar los permisos
        Role::create(['name' => 'gestor-academico'])
            ->givePermissionTo([
                'MODULO_GESTION-ACADEMICA',
                'MENU_ACADEMICO_SEDE',
                'MENU_ACADEMICO_PLAN-ESTUDIO',
                'MENU_ACADEMICO_PLAN-ESTUDIO_GRADO',
                'MENU_ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA',
                'MENU_ACADEMICO_PLAN-ESTUDIO_CARRERA',
                'ACADEMICO_SEDE_TODO',
                'ACADEMICO_PLAN-ESTUDIO_GRADO_TODO',
                'ACADEMICO_PLAN-ESTUDIO_TIPO-CARRERA_TODO',
                'ACADEMICO_PLAN-ESTUDIO_CARRERA_TODO',
            ]);


        Role::create(['name' => 'gestor-ingreso'])
            ->givePermissionTo([
                'MODULO_INGRESO',
                'MENU_INGRESO_CONVOCATORIA',
                'MENU_INGRESO_CONVOCATORIA_GESTIONAR-CONVOCATORIA',
                'MENU_INGRESO_CONVOCATORIA_CARGAR-ARCHIVO',
                'MENU_INGRESO_CONVOCATORIA_CANDIDATOS',
                'INGRESO_CONVOCATORIA_TODO',
                'INGRESO_CONVOCATORIA_CANDIDATOS_LISTADO',
                'INGRESO_CONVOCATORIA_CANDIDATOS_INVITACIONES'
            ]);


        Role::create(['name' => 'aspirante'])
            ->givePermissionTo();

        Role::create(['name' => 'estudiante'])
            ->givePermissionTo();

        Role::create(['name' => 'docente'])
            ->givePermissionTo([
                'ACADEMICO_EVALUACION_TODO'
            ]);
    }
}
