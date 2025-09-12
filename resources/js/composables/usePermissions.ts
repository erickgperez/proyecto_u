import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function usePermissions() {
    const roles = computed(() => usePage().props.auth.roles);
    const permissions = computed(() => usePage().props.auth.permissions);

    const hasPermission = (permissionName: string): boolean => {
        return permissions.value.includes(permissionName) ?? false;
    };

    const hasRole = (roleName: string): boolean => {
        return roles.value.includes(roleName) ?? false;
    };

    const isSuperAdmin = computed<boolean>(() => {
        return roles.value.includes('super-admin') ?? false; // Example for a common super-admin role
    });

    return {
        hasPermission,
        hasRole,
        isSuperAdmin,
        userPermissions: computed(() => permissions.value || []),
        userRoles: computed(() => roles.value || []),
    };
}
