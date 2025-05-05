import { defineStore } from 'pinia';
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface AuthProps {
    [key: string]: any; // Add index signature
    auth: {
        user: any;
        roles: string[];
        permissions: string[];
    };
}

export const usePermissionStore = defineStore('permissions', () => {
    const page = usePage<AuthProps>();
    const permissions = ref<string[]>(page.props.auth.permissions || []);
    const roles = ref<string[]>(page.props.auth.roles || []);

    // Sync with Inertia props on page change
    watch(
        () => page.props.auth,
        (newAuth) => {
            permissions.value = newAuth.permissions || [];
            roles.value = newAuth.roles || [];
        },
        { deep: true }
    );

    const hasPermission = (permission: string): boolean => permissions.value.includes(permission);
    const hasRole = (role: string): boolean => roles.value.includes(role);

    return { permissions, roles, hasPermission, hasRole };
});