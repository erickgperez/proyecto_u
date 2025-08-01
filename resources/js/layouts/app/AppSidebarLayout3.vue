<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';

import { type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const user = page.props.auth.user as User;

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    activeTab?: string;
    isFirstTab?: boolean;
    titulo?: string;
    subtitulo?: string;
    icono?: string;
}
const drawer = ref(true);
const group = ref(null);

const menu = ref(false);
const menuModulos = ref(false);
const modulos = [
    {
        codigo: 'ingreso-universitario',
        nombre: 'Ingreso Universitario',
        icono: 'mdi-account-plus-outline',
    },
    {
        codigo: 'gestion-academica',
        nombre: 'Gestión Académica',
        icono: 'mdi-file-document-edit-outline',
    },
    {
        codigo: 'calificaciones',
        nombre: 'Calificaciones',
        icono: 'mdi-book-education-outline',
    },
];
interface Modulo {
    codigo: string;
    nombre: string;
    icono: string;
}
const moduloActual = ref<Modulo>(modulos[0]);

const handleLogout = () => {
    router.flushAll();
    localStorage.removeItem('active_app_tabs');
};

const emit = defineEmits<{
    loadComponent: [component: string];
}>();

const handleLinkClick = (hrefName: string) => {
    //router.get(route(hrefName), {}, { preserveState: true, preserveScroll: false });

    const componentName = hrefName.replaceAll('-', '');
    emit('loadComponent', componentName);
};

watch(group, () => {
    drawer.value = false;
});

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    activeTab: () => 'tab1',
    isFirstTab: () => true,
    titulo: () => '',
    subtitulo: () => '',
    icono: () => '',
});

onMounted(() => {
    drawer.value = !props.isFirstTab;
});
</script>

<template>
    <v-responsive>
        <v-app>
            <v-navigation-drawer
                :permanent="isFirstTab"
                :expand-on-hover="drawer"
                :rail="drawer"
                :location="$vuetify.display.mobile ? 'bottom' : undefined"
                class="bg-blue-grey-darken-1"
            >
                <v-list class="text-white">
                    <v-list-item prepend-icon="mdi-school" subtitle="descripción" title="Nombre Aplicación">
                        <template v-slot:prepend>
                            <v-avatar>
                                <v-icon color="info" icon="mdi-school" size="x-large"></v-icon>
                            </v-avatar>
                        </template>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>

                <v-list density="compact" nav>
                    <Link :href="route('informe-example')" preserve-state preserve-scroll>
                        <v-list-item
                            prepend-icon="mdi-text-box-multiple-outline"
                            class="text-body-1"
                            :class="$page.url === '/informe/example' ? 'bg-blue-lighten-4' : ''"
                            @click="handleLinkClick('informe-example')"
                        >
                            Informe
                        </v-list-item>
                    </Link>
                    <Link :href="route('crud-example')" preserve-state preserve-scroll>
                        <v-list-item
                            prepend-icon="mdi-form-textbox"
                            class="text-body-1"
                            :class="$page.url === '/crud/example' ? 'bg-blue-lighten-4' : ''"
                            @click="handleLinkClick('crud-example')"
                        >
                            CRUD
                        </v-list-item>
                    </Link>
                    <Link :href="route('profile.edit')" preserve-state preserve-scroll>
                        <v-list-item
                            prepend-icon="mdi-cog"
                            :class="$page.url === '/settings/profile' ? 'bg-blue-lighten-4' : ''"
                            @click="handleLinkClick('profile.edit')"
                        >
                            Configuración
                        </v-list-item>
                    </Link>
                    <v-list-item
                        v-if="moduloActual.codigo == 'ingreso-universitario'"
                        prepend-icon="mdi-town-hall"
                        append-icon="mdi-menu-right"
                        class="text-body-1 text-none text-left"
                    >
                        Bachillerato
                        <v-menu activator="parent">
                            <v-list class="bg-blue-grey-darken-2">
                                <Link :href="route('ingreso-bachillerato-cargar-archivo')" preserve-state preserve-scroll>
                                    <v-list-item
                                        link
                                        prepend-icon="mdi-upload-circle-outline"
                                        title="Cargar archivo"
                                        :class="$page.url === '/ingreso/bachillerato/cargar-archivo' ? 'bg-blue-lighten-4' : ''"
                                        @click="handleLinkClick('ingreso-bachillerato-cargar-archivo')"
                                    >
                                    </v-list-item>
                                </Link>
                                <Link :href="route('ingreso-bachillerato-candidatos')" preserve-state preserve-scroll>
                                    <v-list-item
                                        link
                                        prepend-icon="mdi-account-star-outline"
                                        title="Seleccionar candidatos"
                                        :class="$page.url === '/ingreso/bachillerato/candidatos' ? 'bg-blue-lighten-4' : ''"
                                        @click="handleLinkClick('ingreso-bachillerato-candidatos')"
                                    >
                                    </v-list-item>
                                </Link>

                                <v-list-item v-for="i in 3" :key="i" link append-icon="mdi-menu-right">
                                    <v-list-item-title>Item {{ i }}</v-list-item-title>
                                    <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                                        <v-list class="bg-blue-grey-darken-2">
                                            <v-list-item v-for="j in 3" :key="j" link append-icon="mdi-menu-right">
                                                <v-list-item-title>Item {{ i }} - {{ j }}</v-list-item-title>
                                                <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                                                    <v-list class="bg-blue-grey-darken-2">
                                                        <v-list-item v-for="k in 5" :key="k" link>
                                                            <v-list-item-title>Item {{ i }} - {{ j }} - {{ k }}</v-list-item-title>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-menu>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-list-item>

                    <v-btn
                        v-if="moduloActual.codigo == 'gestion-academica'"
                        variant="text"
                        append-icon="mdi-menu-right"
                        class="text-body-1 text-none text-left"
                    >
                        GESTION ACADEMICA
                        <v-menu activator="parent">
                            <v-list class="bg-blue-grey-darken-2">
                                <v-list-item v-for="i in 3" :key="i" link append-icon="mdi-menu-right">
                                    <v-list-item-title>Gestión académica Item {{ i }}</v-list-item-title>
                                    <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                                        <v-list class="bg-blue-grey-darken-2">
                                            <v-list-item v-for="j in 3" :key="j" link append-icon="mdi-menu-right">
                                                <v-list-item-title>Gestión académica Item {{ i }} - {{ j }}</v-list-item-title>
                                                <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                                                    <v-list class="bg-blue-grey-darken-2">
                                                        <v-list-item v-for="k in 5" :key="k" link>
                                                            <v-list-item-title>Gestión Académica Item {{ i }} - {{ j }} - {{ k }}</v-list-item-title>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-menu>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-btn>

                    <v-btn
                        v-if="moduloActual.codigo == 'calificaciones'"
                        variant="text"
                        append-icon="mdi-menu-right"
                        class="text-body-1 text-none text-left"
                    >
                        CALIFICACIONES
                        <v-menu activator="parent">
                            <v-list class="bg-blue-grey-darken-2">
                                <v-list-item v-for="i in 3" :key="i" link append-icon="mdi-menu-right">
                                    <v-list-item-title>Calificaciones Item {{ i }}</v-list-item-title>
                                    <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                                        <v-list class="bg-blue-grey-darken-2">
                                            <v-list-item v-for="j in 3" :key="j" link append-icon="mdi-menu-right">
                                                <v-list-item-title>Calificaciones Item {{ i }} - {{ j }}</v-list-item-title>
                                                <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                                                    <v-list class="bg-blue-grey-darken-2">
                                                        <v-list-item v-for="k in 5" :key="k" link>
                                                            <v-list-item-title>Calificaciones Item {{ i }} - {{ j }} - {{ k }}</v-list-item-title>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-menu>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-btn>
                </v-list>
            </v-navigation-drawer>
            <v-app-bar :elevation="10" rounded="b-xl">
                <template v-slot:prepend>
                    <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
                </template>
                <template v-slot:append>
                    <v-menu v-model="menuModulos" :close-on-content-click="false">
                        <template v-slot:activator="{ props }">
                            <v-btn
                                color="indigo"
                                v-bind="props"
                                stacked
                                :title="moduloActual.codigo === '' ? 'Módulos' : 'Módulo actual'"
                                :prepend-icon="moduloActual.codigo === '' ? 'mdi-view-module' : moduloActual.icono"
                            >
                                {{ moduloActual.codigo === '' ? 'Módulos' : moduloActual.nombre }}
                            </v-btn>
                        </template>

                        <v-card min-width="300">
                            <v-item-group mandatory>
                                <v-container>
                                    <v-row>
                                        <v-col v-for="modulo in modulos" :key="modulo.codigo">
                                            <v-item>
                                                <v-card
                                                    :color="moduloActual.codigo == modulo.codigo ? 'primary' : ''"
                                                    class="d-flex align-center"
                                                    height="150"
                                                    width="150"
                                                    dark
                                                    @click="moduloActual = modulo"
                                                >
                                                    <v-btn :prepend-icon="modulo.icono" stacked variant="plain" height="150" width="150">
                                                        {{ modulo.nombre }}
                                                    </v-btn>
                                                </v-card>
                                            </v-item>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-item-group>
                        </v-card>
                    </v-menu>
                    <v-list rounded="b-xl">
                        <v-menu v-model="menu" :close-on-content-click="false" location="bottom center">
                            <template v-slot:activator="{ props }">
                                <v-list-item
                                    prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
                                    :subtitle="user.email"
                                    :title="user.name"
                                    v-bind="props"
                                ></v-list-item>
                            </template>

                            <v-card>
                                <v-list>
                                    <v-list-item prepend-icon="mdi-cog">
                                        <Link
                                            :href="route('profile.edit')"
                                            prefetch
                                            as="button"
                                            :only="['tabContent']"
                                            preserve-state
                                            preserve-scroll
                                        >
                                            Settings
                                        </Link>
                                    </v-list-item>

                                    <v-list-item prepend-icon="mdi-logout">
                                        <Link class="block" method="post" :href="route('logout')" @click="handleLogout" as="button"> Log out </Link>
                                    </v-list-item>
                                </v-list>
                            </v-card>
                        </v-menu>
                    </v-list>
                </template>

                <v-app-bar-title>
                    <v-card class="mx-auto" :prepend-icon="props.icono">
                        <template v-slot:title>
                            <span class="font-weight-black text-info">{{ props.titulo }}</span>
                        </template>
                        <template v-slot:subtitle>
                            <span class="font-weight-black">{{ props.subtitulo }}</span>
                        </template>
                        <template v-slot:prepend>
                            <v-icon size="x-large" color="info"></v-icon>
                        </template>
                    </v-card>
                </v-app-bar-title>
            </v-app-bar>
            <v-main class="mt-1">
                <v-card class="bg-surface-light mx-auto">
                    <v-card-text class="pt-4">
                        <v-sheet class="mx-auto">
                            <slot />
                        </v-sheet>
                    </v-card-text>
                </v-card>
            </v-main>
        </v-app>
    </v-responsive>
</template>
