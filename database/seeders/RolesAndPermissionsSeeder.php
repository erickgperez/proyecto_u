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
        Permission::create(['name' => 'modulos']);
        Permission::create(['name' => 'modulo_ingreso']);
        Permission::create(['name' => 'modulo_calificaciones']);
        Permission::create(['name' => 'modulo_gestion_academica']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // Crear roles y asignar los permisos
        $role = Role::create(['name' => 'gestor_academico'])
            ->givePermissionTo(['modulos', 'modulo_gestion_academica']);

        $role = Role::create(['name' => 'gestor_ingreso'])
            ->givePermissionTo(['modulos', 'modulo_ingreso']);


        $role = Role::create(['name' => 'aspirante'])
            ->givePermissionTo();

        $role = Role::create(['name' => 'super-admin'])
            ->givePermissionTo(Permission::all());
    }
}
