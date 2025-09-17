import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function usePermissions() {
    const roles = computed(() => usePage().props.auth.roles);
    const permissions = computed(() => usePage().props.auth.permissions);

    const hasPermission = (permissionName: string): boolean => {
        //Verifica que tenga el permiso o que tenga el rol super-admin
        const todo = permissionName.split('_').slice(-1)[0];
        return permissions.value.includes(permissionName) || isSuperAdmin.value || todo === 'TODO';
    };

    const hasAnyPermission = (permissionsName: string[]): boolean => {
        //Verifica que tenga alguno de los permisos o que tenga el rol super-admin
        const setPermissions = new Set(permissionsName);
        return isSuperAdmin.value || permissions.value.some((el) => setPermissions.has(el));
    };

    const hasRole = (roleName: string): boolean => {
        return roles.value.includes(roleName);
    };

    const isSuperAdmin = computed<boolean>(() => {
        return roles.value.includes('super-admin');
    });

    return {
        hasPermission,
        hasAnyPermission,
        hasRole,
        isSuperAdmin,
        userPermissions: computed(() => permissions.value || []),
        userRoles: computed(() => roles.value || []),
    };
}
