<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'modulos']);
        Permission::create(['name' => 'modulo_ingreso']);
        Permission::create(['name' => 'modulo_calificaciones']);
        Permission::create(['name' => 'modulo_gestion_academica']);

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'gestor_academico']);
        $role->givePermissionTo(['modulos', 'modulo_gestion_academica']);

        // or may be done by chaining
        $role = Role::create(['name' => 'aspirante'])
            ->givePermissionTo();

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
