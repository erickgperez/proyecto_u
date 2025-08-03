<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout3.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    titulo?: string;
    subtitulo?: string;
    icono?: string;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    titulo: () => '',
    subtitulo: () => '',
    icono: () => '',
});
</script>

<template>
    <AppSidebarLayout :isFirstTab="true" :breadcrumbs="breadcrumbs" :activeTab="activeTab" :titulo="titulo" :subtitulo="subtitulo" :icono="icono">
        <slot />
    </AppSidebarLayout>
</template>
