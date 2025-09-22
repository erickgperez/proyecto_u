<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import DashboardAspirante from './DashboardAspirante.vue';

const { hasRole } = usePermissions();
</script>

<template>
    <Head title="Bienvenido"> </Head>
    <AppLayout>
        <DashboardAspirante v-if="hasRole('aspirante')"></DashboardAspirante>
    </AppLayout>
</template>
