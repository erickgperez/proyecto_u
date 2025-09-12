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
        Permission::create(['name' => 'MODULOS']);
        Permission::create(['name' => 'MODULO_INGRESO']);
        Permission::create(['name' => 'MODULO_CALIFICACIONES']);
        Permission::create(['name' => 'MODULO_GESTION_ACADEMICA']);

        //************ MENUS
        Permission::create(['name' => 'MENU_INGRESO_CONVOCATORIA']);
        Permission::create(['name' => 'MENU_INGRESO_CONVOCATORIA_GESTIONAR']);
        Permission::create(['name' => 'MENU_INGRESO_CONVOCATORIA_CARGAR_ARCHIVO']);
        Permission::create(['name' => 'MENU_INGRESO_CONVOCATORIA_CANDIDATOS']);

        //********** GESTIONAR CONVOCATORIA
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_CREAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_EXPORTAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_EDITAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_MOSTRAR']);
        Permission::create(['name' => 'INGRESO_CONVOCATORIA_BORRAR']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // Crear roles y asignar los permisos
        $role = Role::create(['name' => 'gestor-academico'])
            ->givePermissionTo(['MODULOS', 'MODULO_GESTION_ACADEMICA']);

        $role = Role::create(['name' => 'gestor-ingreso'])
            ->givePermissionTo([
                'MODULOS',
                'MODULO_INGRESO',
                'MENU_INGRESO_CONVOCATORIA',
                'MENU_INGRESO_CONVOCATORIA_GESTIONAR',
                'MENU_INGRESO_CONVOCATORIA_CARGAR_ARCHIVO',
                'MENU_INGRESO_CONVOCATORIA_CANDIDATOS',
                'INGRESO_CONVOCATORIA_CREAR',
                'INGRESO_CONVOCATORIA_EXPORTAR',
                'INGRESO_CONVOCATORIA_EDITAR',
                'INGRESO_CONVOCATORIA_MOSTRAR',
                'INGRESO_CONVOCATORIA_BORRAR'
            ]);


        $role = Role::create(['name' => 'aspirante'])
            ->givePermissionTo();

        $role = Role::create(['name' => 'super-admin']);
        // No se le dan permisos, este es el único rol que se verifica para dejar realizar cualquier acción
        //->givePermissionTo(Permission::all());
    }
}
