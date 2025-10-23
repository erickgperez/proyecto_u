<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { SortBy } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();

const { t } = useI18n();

interface Opcion {
    id: number;
    carrera_sede_id: number;
    carrera: string;
    opcion: string;
}
interface Solicitud {
    id: number;
    nie: string;
    nombre: string;
    sector: string;
    nota: number;
    seleccionado: boolean;
    PRIMERA_OPCION: Opcion | null;
    SEGUNDA_OPCION: Opcion | null;
    TERCERA_OPCION: Opcion | null;
}
interface Sede {
    id: number;
    nombre: string;
}
interface CarreraSede {
    id: number;
    sede: Sede;
    carrera: string;
    carrera_tipo: string;
    carrera_nombre: string;
    seleccionados: number;
    cupo: number;
    seleccionados_publico: number;
    seleccionados_privado: number;
}
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

const loading = ref(false);
const convocatoria = ref<Convocatoria | null>(null);
const sede = ref<Sede | null>(null);
const carrerasSede = ref<CarreraSede[]>([]);
const solicitudes = ref<Solicitud[]>([]);

const headers = [
    { title: t('aspirante._nie_'), key: 'nie' },
    { title: t('_nombre_'), key: 'nombre' },
    { title: t('aspirante._nota_'), key: 'nota' },
    { title: t('_sector_'), key: 'sector' },
    { title: t('aspirante._seleccionado_'), key: 'seleccionado' },
    { title: t('aspirante._opciones_'), key: 'opciones' },
];
const search = ref('');

const sortBy: SortBy[] = [
    { key: 'fecha', order: 'asc' },
    { key: 'nombre', order: 'asc' },
];

const drawer = ref(true);

const sedes = computed(() => {
    const sedes_ = convocatoria.value?.carreras_sedes.map((cs: CarreraSede) => cs.sede);
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
        loading.value = true;
        axios
            .get(route('ingreso-convocatoria-solicitudes', { id: convocatoria.value.id, idSede: sede.value.id }))
            .then(function (response) {
                carrerasSede.value = response.data.ofertaSede;
                solicitudes.value = response.data.solicitudes;
            })
            .catch(function (error) {
                // handle error
                console.error('Error fetching data:', error);
            })
            .finally(function () {
                loading.value = false;
            });
    }
}
</script>

<template>
    <Head :title="$t('aspirante._seleccion_')"></Head>

    <AppLayout
        :titulo="$t('aspirante._seleccion_aspirantes_')"
        :subtitulo="$t('aspirante._seleccion_descripcion_')"
        icono="mdi-account-filter-outline"
    >
        <v-card v-if="hasPermission('MENU_INGRESO_SELECCION')" class="elevation-12 rounded-xl">
            <v-card-title color="primary" class="bg-blue-accent-2 pa-5">
                <v-row>
                    <div class="text-font-weight-black">{{ convocatoria?.descripcion }}</div>
                    <v-spacer></v-spacer>
                    <DIV>Sede: {{ sede?.nombre }}</DIV>
                </v-row>
            </v-card-title>
            <v-card-text class="pa-0">
                <v-layout>
                    <v-navigation-drawer location="right" v-model="drawer" color="blue-grey-lighten-4" :width="400">
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
                                :loading="loading"
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
                            <v-card v-for="cs in carrerasSede" :key="cs.id">
                                <v-card-text>
                                    <div class="text-caption text-green-darken-3">{{ cs.carrera }}</div>
                                    <div class="text-center">
                                        <v-progress-linear :model-value="cs.seleccionados / cs.cupo" color="blue-grey" height="25">
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
                                </v-card-text>
                            </v-card>
                        </v-list>
                    </v-navigation-drawer>
                    <v-main class="w-100 overflow-y-scroll rounded-l-xl text-4xl" style="height: 85dvh">
                        <v-data-table
                            :headers="headers"
                            :items="solicitudes"
                            density="compact"
                            border="primary thin"
                            class="w-100"
                            multi-sort
                            hover
                            striped="odd"
                        >
                            <template v-slot:item="{ item }">
                                <tr
                                    class="text-no-wrap"
                                    :class="{
                                        'bg-green-lighten-4': item.seleccionado,
                                    }"
                                >
                                    <td>{{ item.nie }}</td>
                                    <td>{{ item.nombre }}</td>
                                    <td>{{ item.nota }}</td>
                                    <td>{{ item.sector }}</td>
                                    <td>
                                        <v-checkbox-btn v-model="item.seleccionado" :ripple="false"></v-checkbox-btn>
                                    </td>
                                    <td>
                                        <v-list density="compact" bg-color="transparent">
                                            <v-list-item v-if="item.PRIMERA_OPCION">
                                                <template v-slot:prepend>
                                                    <v-icon icon="mdi-numeric-1"></v-icon>
                                                </template>

                                                <v-list-item-title>
                                                    {{ item.PRIMERA_OPCION.carrera }}
                                                </v-list-item-title>
                                            </v-list-item>
                                            <v-list-item v-if="item.SEGUNDA_OPCION">
                                                <template v-slot:prepend>
                                                    <v-icon icon="mdi-numeric-2"></v-icon>
                                                </template>

                                                <v-list-item-title>
                                                    {{ item.SEGUNDA_OPCION.carrera }}
                                                </v-list-item-title>
                                            </v-list-item>
                                            <v-list-item v-if="item.TERCERA_OPCION">
                                                <template v-slot:prepend>
                                                    <v-icon icon="mdi-numeric-3"></v-icon>
                                                </template>

                                                <v-list-item-title>
                                                    {{ item.TERCERA_OPCION?.carrera }}
                                                </v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-main>
                </v-layout>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
