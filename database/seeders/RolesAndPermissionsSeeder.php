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
        Permission::create(['name' => 'MENU_ADMINISTRACION_PERSONA']);
        Permission::create(['name' => 'ADMINISTRACION_PERSONA_TODO']);
        Permission::create(['name' => 'ADMINISTRACION_PERSONA_CREAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERSONA_EXPORTAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERSONA_EDITAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERSONA_MOSTRAR']);
        Permission::create(['name' => 'ADMINISTRACION_PERSONA_BORRAR']);

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

        Permission::create(['name' => 'MENU_INGRESO_CALIFICACION-BACHILLERATO']);

        Permission::create(['name' => 'MENU_INGRESO_ASPIRANTES']);
        Permission::create(['name' => 'MENU_INGRESO_ASPIRANTES_SELECCION']);

        //***** GESTION ACADEMICA
        // SEDES
        Permission::create(['name' => 'MENU_ACADEMICA_SEDES']);
        Permission::create(['name' => 'ACADEMICA_SEDE_TODO']);
        Permission::create(['name' => 'ACADEMICA_SEDE_CREAR']);
        Permission::create(['name' => 'ACADEMICA_SEDE_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICA_SEDE_EDITAR']);
        Permission::create(['name' => 'ACADEMICA_SEDE_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICA_SEDE_BORRAR']);

        Permission::create(['name' => 'MENU_ACADEMICA_PLAN-ESTUDIO']);
        Permission::create(['name' => 'MENU_ACADEMICA_PLAN-ESTUDIO_GRADO']);
        Permission::create(['name' => 'MENU_ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA']);
        Permission::create(['name' => 'MENU_ACADEMICA_PLAN-ESTUDIO_CARRERA']);

        // GRADO
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_GRADO_TODO']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_GRADO_CREAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_GRADO_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_GRADO_EDITAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_GRADO_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_GRADO_BORRAR']);

        // TIPO_CARRERA
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_TODO']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_CREAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_EDITAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_BORRAR']);

        // CARRERA
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_CARRERA_TODO']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_CARRERA_CREAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_CARRERA_EXPORTAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_CARRERA_EDITAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_CARRERA_MOSTRAR']);
        Permission::create(['name' => 'ACADEMICA_PLAN-ESTUDIO_CARRERA_BORRAR']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // Crear roles y asignar los permisos
        $role = Role::create(['name' => 'gestor-academico'])
            ->givePermissionTo([
                'MODULO_GESTION-ACADEMICA',
                'MENU_ACADEMICA_SEDES',
                'MENU_ACADEMICA_PLAN-ESTUDIO',
                'MENU_ACADEMICA_PLAN-ESTUDIO_GRADO',
                'MENU_ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA',
                'MENU_ACADEMICA_PLAN-ESTUDIO_CARRERA',
                'ACADEMICA_SEDE_TODO',
                'ACADEMICA_PLAN-ESTUDIO_GRADO_TODO',
                'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_TODO',
                'ACADEMICA_PLAN-ESTUDIO_CARRERA_TODO',
            ]);


        $role = Role::create(['name' => 'gestor-ingreso'])
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


        $role = Role::create(['name' => 'aspirante'])
            ->givePermissionTo();

        $role = Role::create(['name' => 'super-admin']);
        // No se le dan permisos, este es el único rol que se verifica para dejar realizar cualquier acción
        //->givePermissionTo(Permission::all());
    }
}
