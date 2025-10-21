<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { SortBy } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, PropType, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();

const { t } = useI18n();

interface Convocatoria {
    id: number;
    nombre: string;
    descripcion: string;
    carreras_sedes: [];
}

const props = defineProps({
    convocatorias: {
        type: Array as PropType<Convocatoria[]>,
        required: true,
        default: () => [],
    },
});

const convocatoria = ref<Convocatoria | null>(null);
const sede = ref(null);
const carrerasSede = ref(null);
const solicitudes = ref([]);

const headers = [
    { title: t('aspirante._nie_'), key: 'nie' },
    { title: t('_nombre_'), key: 'nombre' },
    { title: t('aspirante._nota_'), key: 'nota' },
    { title: t('aspirante._opciones_'), key: 'opciones' },
];
const search = ref('');

const sortBy: SortBy[] = [
    { key: 'fecha', order: 'asc' },
    { key: 'nombre', order: 'asc' },
];

const drawer = ref(true);

const sedes = computed(() => {
    const sedes_ = convocatoria.value?.carreras_sedes.map((cs) => cs.sede);
    return sedes_?.filter((obj, index, self) => index === self.findIndex((t) => t.id === obj.id));
});

function itemProps(item: Convocatoria) {
    return {
        id: item.id,
        title: item.nombre,
        subtitle: item.descripcion,
    };
}

function cargarSolicitudes() {
    if (convocatoria.value != null && sede.value != null) {
        axios
            .get(route('ingreso-convocatoria-solicitudes', { id: convocatoria.value.id, idSede: sede.value.id }))
            .then(function (response) {
                carrerasSede.value = response.data.ofertaSede;
                solicitudes.value = response.data.solicitudes;
            })
            .catch(function (error) {
                // handle error
                console.error('Error fetching data:', error);
            });
    }
}

watch(convocatoria, () => {
    sede.value = null;
});
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
                        <v-list-item class="font-weight-black text-primary">
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
                    <v-select
                        clearable
                        v-model="convocatoria"
                        :label="$t('convocatoria._convocatoria_')"
                        :items="props.convocatorias"
                        :item-props="itemProps"
                    ></v-select>
                    <v-select
                        clearable
                        v-model="sede"
                        :label="$t('sede._sede_')"
                        :items="sedes"
                        item-title="nombre"
                        item-id="id"
                        return-object
                    ></v-select>
                    <v-row justify="center">
                        <v-btn
                            color="primary"
                            rounded="xl"
                            variant="elevated"
                            @click="cargarSolicitudes"
                            :disabled="convocatoria == null || sede == null"
                            >{{ $t('_cargar_') }}</v-btn
                        >
                    </v-row>
                </v-navigation-drawer>

                <v-navigation-drawer location="right" permanent class="rounded-r-xl" width="350">
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

                    <v-divider></v-divider>

                    <v-list density="compact" nav>
                        <v-card v-for="cs in carrerasSede" :key="id">
                            <v-card-text>
                                <div class="text-caption text-green-darken-3">{{ cs.carrera }}</div>
                                <div class="text-center">
                                    <v-progress-linear :model-value="cs.seleccionados / cs.crupo" color="blue-grey" height="25">
                                        <strong>seleccionados {{ cs.seleccionados }}/{{ cs.cupo }} </strong>
                                    </v-progress-linear>
                                    <v-progress-circular
                                        :model-value="cs.seleccionados_publico"
                                        :rotate="360"
                                        :size="100"
                                        :width="15"
                                        color="primary"
                                    >
                                        <template v-slot:default>
                                            {{ cs.seleccionados > 0 ? (cs.seleccionados_publico / cs.seleccionados) * 100 : 0 }}% <Br /> Público
                                        </template>
                                    </v-progress-circular>
                                    <v-progress-circular
                                        :model-value="cs.seleccionados_privado"
                                        :rotate="360"
                                        :size="100"
                                        :width="15"
                                        color="secundary"
                                    >
                                        <template v-slot:default>
                                            {{ cs.seleccionados > 0 ? (cs.seleccionados_privado / cs.seleccionados) * 100 : 0 }}% <Br /> Privado
                                        </template>
                                    </v-progress-circular>
                                </div>

                                <!--<div class="d-flex justify-space-between py-3">
                                    <span class="text-green-darken-3 font-weight-medium"> $26,442.00 remitted </span>

                                    <span class="text-medium-emphasis"> $29,380.00 total </span>
                                </div>-->
                            </v-card-text>

                            <!--<v-divider></v-divider>

                            <v-list-item append-icon="mdi-chevron-right" lines="two" subtitle="Details and agreement" link></v-list-item>
                        -->
                        </v-card>
                    </v-list>
                </v-navigation-drawer>
                <v-main class="overflow-y-scroll text-4xl" style="height: 90dvh">
                    <v-container>
                        <v-data-table
                            :headers="headers"
                            :items="solicitudes"
                            density="compact"
                            border="primary thin"
                            class="w-100"
                            multi-sort
                            hover
                            fixed-header
                            striped="odd"
                        >
                            <template v-slot:item.opciones="{ value, item }">
                                <v-list density="compact" bg-color="transparent">
                                    <v-list-item>
                                        <template v-slot:prepend>
                                            <v-icon icon="mdi-numeric-1"></v-icon>
                                        </template>

                                        <v-list-item-title v-text="item.PRIMERA_OPCION.carrera"></v-list-item-title>
                                    </v-list-item>
                                    <v-list-item>
                                        <template v-slot:prepend>
                                            <v-icon icon="mdi-numeric-2"></v-icon>
                                        </template>

                                        <v-list-item-title v-text="item.SEGUNDA_OPCION.carrera"></v-list-item-title>
                                    </v-list-item>
                                    <v-list-item>
                                        <template v-slot:prepend>
                                            <v-icon icon="mdi-numeric-3"></v-icon>
                                        </template>

                                        <v-list-item-title v-text="item.TERCERA_OPCION.carrera"></v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </template>
                        </v-data-table>
                    </v-container>
                </v-main>
            </v-layout>
        </v-sheet>
    </AppLayout>
</template>
