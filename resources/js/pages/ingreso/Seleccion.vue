<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();

const { t } = useI18n();

interface Convocatoria {
    id: number;
    nombre: string;
    descripcion: string;
}
interface Props {
    convocatorias: Convocatoria[];
}

const convocatoria = ref<Convocatoria | null>(null);

const props = defineProps<Props>();

const drawer = ref(true);

const tab = ref('option-1');
</script>

<template>
    <Head :title="$t('aspirante._seleccion_')"></Head>

    <AppLayout
        :titulo="$t('aspirante._seleccion_aspirantes_')"
        :subtitulo="$t('aspirante._seleccion_descripcion_')"
        icono="mdi-account-filter-outline"
    >
        <v-sheet v-if="hasPermission('MENU_INGRESO_SELECCION')" class="elevation-12 rounded-xl">
            <v-layout>
                <v-navigation-drawer location="right" v-model="drawer" color="blue-grey-lighten-4" class="rounded-xl" :width="400">
                    <v-list>
                        <v-list-item>
                            <template v-slot:append>
                                <v-btn
                                    icon="mdi-close"
                                    :title="$t('_cerrar_parametros_')"
                                    color="primary"
                                    size="small"
                                    variant="text"
                                    @click.stop="drawer = !drawer"
                                ></v-btn>
                            </template>
                            <template v-slot:prepend>
                                <v-icon icon="mdi-application-cog" size="small" variant="text"></v-icon>
                            </template>
                            {{ $t('_parametros_') }}
                        </v-list-item>
                    </v-list>

                    <v-divider></v-divider>
                    <v-select clearable label="Select" :items="['California', 'Colorado', 'Florida', 'Georgia', 'Texas', 'Wyoming']"></v-select>
                    <v-list density="compact" nav>
                        <v-list-item prepend-icon="mdi-view-dashboard" value="dashboard"> </v-list-item>

                        <v-list-item prepend-icon="mdi-forum" value="messages"></v-list-item>
                    </v-list>
                </v-navigation-drawer>
                <v-navigation-drawer location="right" permanent :width="350">
                    <v-fab
                        :active="!drawer"
                        color="primary"
                        location="top right"
                        absolute
                        icon="mdi-application-cog"
                        variant="tonal"
                        @click.stop="drawer = !drawer"
                        :title="$t('_parametros_')"
                    ></v-fab>
                    <template v-slot:prepend>
                        <v-list-item
                            lines="two"
                            prepend-avatar="https://randomuser.me/api/portraits/women/81.jpg"
                            subtitle="Logged in"
                            title="Jane Smith"
                        ></v-list-item>
                    </template>

                    <v-divider></v-divider>

                    <v-list density="compact" nav>
                        <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item>
                        <v-list-item prepend-icon="mdi-account" title="My Account" value="account"></v-list-item>
                        <v-list-item prepend-icon="mdi-account-group-outline" title="Users" value="users"></v-list-item>
                    </v-list>
                </v-navigation-drawer>
                <v-main class="pa-2 overflow-y-scroll" style="height: 89dvh">
                    Hola
                    <BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR /><BR />hola
                </v-main>
            </v-layout>
        </v-sheet>
    </AppLayout>
</template>
