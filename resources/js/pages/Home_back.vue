<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, defineAsyncComponent, markRaw, ref, watch } from 'vue';

const CrudExample = defineAsyncComponent(() => import('@/pages/CrudExample.vue'));
const InformeExample = defineAsyncComponent(() => import('@/pages/InformeExample.vue'));
const ProfileContent = defineAsyncComponent(() => import('@/pages/settings/Profile.vue'));
const DashboardContent = defineAsyncComponent(() => import('@/pages/Dashboard.vue'));
const IngresoBachilleratoCargarArchivo = defineAsyncComponent(() => import('@/pages/ingreso/CargarDatosBachillerato.vue'));
const SeleccionCandidatos = defineAsyncComponent(() => import('@/pages/ingreso/SeleccionCandidatos.vue'));

// Los componentes que pueden ser cargados dinamicamente
const tabsComponent = markRaw({
    dashboard: DashboardContent,
    settings: ProfileContent,
    informeExample: InformeExample,
    crudExample: CrudExample,
    ingresoBachilleratoCargarArchivo: IngresoBachilleratoCargarArchivo,
    seleccionCandidatos: SeleccionCandidatos,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page_ = usePage();

const isFirstTab = ref(false);

const tab = ref('tab1');
interface TabsData {
    tab1: { componente: string | null; titulo: string | null; pestania: string | null };
    tab2: { componente: string | null; titulo: string | null; pestania: string | null };
    tab3: { componente: string | null; titulo: string | null; pestania: string | null };
    tab4: { componente: string | null; titulo: string | null; pestania: string | null };
}

// Llevará los datos de los componentes que se cargan en las pestañas
const tabsData: TabsData = {
    tab1: { componente: null, titulo: null, pestania: null },
    tab2: { componente: null, titulo: null, pestania: null },
    tab3: { componente: null, titulo: null, pestania: null },
    tab4: { componente: null, titulo: null, pestania: null },
};

const activeComponentTab = computed(() => {
    return (tabContentInfo: any) => {
        if (tabContentInfo.componente !== null) {
            return tabsComponent[tabContentInfo.componente];
        }
    };
});
const singleComponent = computed(() => {
    return () => {
        return tabsComponent[props.componente];
    };
});

const currentUrl = computed(() => page_.props.request);

const closeTabContent = (tabKey: any) => {
    tabKey.componente = null;
    tabKey.titulo = null;
    tabKey.pestania = null;

    //provocar un cambio de tab para que se actualice el contenido de la pestaña
    tab.value = 'tab0';
    tab.value = 'tab1';

    //Generar una ruta falsa para que se actualice la interfaz
    router.get('/c', {}, { replace: false, preserveScroll: true, preserveState: true });
};

watch(currentUrl, () => {
    //Cuando se cargue una nueva url, provocar un cambio en tab para que se actualice ese sección de la interfaz de usuario
    const tabVal = tab.value;
    tab.value = '';
    tab.value = tabVal;

    const tabKey: keyof TabsData = tab.value as keyof TabsData;
    const existeComponente = Object.values(tabsData).find((elm) => elm.componente == props.componente);

    // Si el elemento no se ha cargado, registrarlo
    if (currentUrl.value !== null && currentUrl.value !== '/c' && !existeComponente) {
        tabsData[tabKey].componente = props.componente as string;
        tabsData[tabKey].titulo = props.titulo as string;
        tabsData[tabKey].pestania = tabVal as string;
    }
    // Si el elemento ya se ha cargado solo mostrar la pestaña en que está
    if (existeComponente) {
        tab.value = existeComponente.pestania;
    }
});

interface Props {
    mustVerifyEmail?: boolean;
    status?: string;
    componente?: string;
    titulo?: string;
}

const handleIsFirstTabMessage = (estado: boolean) => {
    isFirstTab.value = estado;
};

const handleLoadComponentMessage = (component: string) => {
    const tabComponente = Object.values(tabsData).find((elm) => elm.componente?.toLowerCase() == component.toLowerCase());

    if (tabComponente) {
        tab.value = tabComponente.pestania;
    }
};

const props = defineProps<Props>();
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppLayout :breadcrumbs="breadcrumbs" :activeTab="tab" @changeIsFirstTab="handleIsFirstTabMessage" @loadComponent="handleLoadComponentMessage">
        <div class="d-flex flex-row" v-if="isFirstTab && !$vuetify.display.mobile">
            <v-tabs
                v-model="tab"
                direction="vertical"
                align-tabs="center"
                height="60"
                slider-color="#f78166"
                selected-class="border-sm rounded-lg elevation-5 text-primary"
            >
                <v-tab
                    prepend-icon="mdi-tab"
                    :title="'Espacio de trabajo ' + (index + 1)"
                    :text="'Espacio ' + (index + 1)"
                    v-for="(url, tab_, index) in tabsData"
                    :value="tab_"
                    :key="tab_"
                    class="v-list-item"
                >
                </v-tab>
            </v-tabs>

            <v-tabs-window v-model="tab" class="w-100 pl-5">
                <v-tabs-window-item v-for="(data, tabKey_) in tabsData" :value="tabKey_" :key="tabKey_">
                    <v-layout v-if="data.componente != null">
                        <v-system-bar color="primary" class="mx-auto">
                            <span class="text-h6 text-white">{{ data.titulo }}</span>
                            <v-spacer></v-spacer>
                            <v-icon @click="closeTabContent(data)">mdi-close</v-icon>
                        </v-system-bar>

                        <v-card class="w-full pt-5">
                            <KeepAlive><component :is="activeComponentTab(data)"></component></KeepAlive>
                        </v-card>
                    </v-layout>
                </v-tabs-window-item>
            </v-tabs-window>
        </div>
        <div class="d-flex w-full flex-row pl-5" v-if="!isFirstTab || $vuetify.display.mobile">
            <v-card class="w-full pt-5"><component :is="singleComponent()"></component></v-card>
        </div>
    </AppLayout>
</template>
