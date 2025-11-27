<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import DashboardAspirante from './DashboardAspirante.vue';
import DashboardEstudiante from './DashboardEstudiante.vue';

const { hasRole } = usePermissions();
const page = usePage();
const persona = page.props.auth.persona;
</script>

<template>
    <Head :title="persona.sexo?.descripcion ==='Femenino' ? $t('_bienvenida_') : $t('_bienvenido_')"> </Head>
    <AppLayout>
        <DashboardAspirante v-if="hasRole('aspirante')"></DashboardAspirante>
        <DashboardEstudiante v-if="hasRole('estudiante')"></DashboardEstudiante>
    </AppLayout>
</template>
