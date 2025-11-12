<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import DashboardAspirante from './DashboardAspirante.vue';
import DashboardEstudiante from './DashboardEstudiante.vue';

const { hasRole } = usePermissions();
</script>

<template>
    <Head title="Bienvenido"> </Head>
    <AppLayout>
        <DashboardAspirante v-if="hasRole('aspirante')"></DashboardAspirante>
        <DashboardEstudiante v-if="hasRole('estudiante')"></DashboardEstudiante>
    </AppLayout>
</template>
