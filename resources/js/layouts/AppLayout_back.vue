<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout3.vue';
import type { BreadcrumbItemType } from '@/types';
import { onMounted, onUnmounted, ref, watch } from 'vue';

const isFirstTabOpen = ref(true);
const retrievedTabs = ref<number[]>([]);
const instanceId = Date.now(); // Unique ID for this tab

onMounted(() => {
    window.addEventListener('beforeunload', handleBeforeUnload);

    //localStorage.removeItem('active_app_tabs');
    //Recuperar los ids de las pestañas abiertas
    retrievedTabs.value = JSON.parse(localStorage.getItem('active_app_tabs') || '[]');
    detectIsFirstTabOpen();
    retrievedTabs.value.push(instanceId);

    //Guardarla en localStorage
    localStorage.setItem('active_app_tabs', JSON.stringify(retrievedTabs.value));

    window.addEventListener('storage', handleStorageChange);
    emit('changeIsFirstTab', isFirstTabOpen.value);
});

onUnmounted(() => {
    removeTabId();
    window.removeEventListener('beforeunload', handleBeforeUnload);
    window.removeEventListener('storage', handleStorageChange);
});

const removeTabId = () => {
    retrievedTabs.value = JSON.parse(localStorage.getItem('active_app_tabs') || '[]');

    //Borrar el de la actual
    retrievedTabs.value = retrievedTabs.value.filter((id) => id != instanceId);

    // Guardar las restantes en localStorage
    localStorage.setItem('active_app_tabs', JSON.stringify(retrievedTabs.value));
};

function handleBeforeUnload(event: eforeUnloadEvent) {
    //event.preventDefault();
    //event.returnValue = '';
    removeTabId();
}

function detectIsFirstTabOpen() {
    retrievedTabs.value = JSON.parse(localStorage.getItem('active_app_tabs') || '[]');

    // Determinar si es la primera
    if (retrievedTabs.value.length === 0) {
        isFirstTabOpen.value = true;
    } else {
        isFirstTabOpen.value = retrievedTabs.value[0] == instanceId;
    }
}

const handleStorageChange = (event: StorageEvent) => {
    if (event.key === 'active_app_tabs') {
        //console.log(`Local storage key 'yourLocalStorageKey' changed to: ${event.newValue}`);
        detectIsFirstTabOpen();
    }
};
const emit = defineEmits<{
    changeIsFirstTab: [estado: boolean];
    loadComponent: [component: string];
}>();

watch(isFirstTabOpen, () => {
    emit('changeIsFirstTab', isFirstTabOpen.value);
});

const handleLoadComponentMessage = (component: string) => {
    emit('loadComponent', component);
};

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    activeTab?: string;
    titulo?: string;
    subtitulo?: string;
    icono?: string;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    activeTab: () => 'tab1',
    titulo: () => '',
    subtitulo: () => '',
    icono: () => '',
});
</script>

<template>
    <AppSidebarLayout
        v-if="isFirstTabOpen"
        :isFirstTab="true"
        :breadcrumbs="breadcrumbs"
        :activeTab="activeTab"
        :titulo="titulo"
        :subtitulo="subtitulo"
        :icono="icono"
        @loadComponent="handleLoadComponentMessage"
    >
        <slot />
    </AppSidebarLayout>
    <AppSidebarLayout
        v-if="!isFirstTabOpen"
        :isFirstTab="false"
        :breadcrumbs="breadcrumbs"
        :activeTab="activeTab"
        @loadComponent="handleLoadComponentMessage"
    >
        <slot />
    </AppSidebarLayout>
    <!--<AppSimpleLayout v-if="!isFirstTabOpen" :breadcrumbs="breadcrumbs" :activeTab="activeTab">
        <slot />
    </AppSimpleLayout>-->
</template>
