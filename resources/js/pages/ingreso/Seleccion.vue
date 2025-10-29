<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { SortBy } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();

const { t } = useI18n();

interface Opcion {
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
    carrera_sede_id: number | null;
    solicitud_carrera_sede_id: number | null;
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

interface Configuracion {
    id: number;
    fecha_publicacion_resultados: Date | null;
    cuota_sector_publico: number | null;
}

interface Convocatoria {
    id: number;
    nombre: string;
    descripcion: string;
    carreras_sedes: [];
    configuracion: Configuracion | null;
}

interface InfoSede {
    cupoSede: number;
    seleccionadosSede: number;
    seleccionadosPublicoSede: number;
    seleccionadosPrivadoSede: number;
}

const props = defineProps({
    convocatorias: {
        type: Array as PropType<Convocatoria[]>,
        required: true,
        default: () => [],
    },
});

const loading = ref(false);
const ejecutandoSeleccionAutomatica = ref(false);
const convocatoria = ref<Convocatoria | null>(null);
const sede = ref<Sede | null>(null);
const infoSede = ref<InfoSede>({ cupoSede: 0, seleccionadosSede: 0, seleccionadosPublicoSede: 0, seleccionadosPrivadoSede: 0 });
const carrerasSede = ref<CarreraSede[]>([]);
const ultimaSeleccion = ref<CarreraSede | null>(null);
const solicitudes = ref<Solicitud[]>([]);
const tipoSeleccion = ref(null);
const snackbar = ref(false);
const overlay = ref(false);
const text = ref('');
const step = ref(1);

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
    { key: 'nota', order: 'desc' },
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
                infoSede.value = response.data.infoSede;
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

function seleccionar(item: Solicitud, opcion = 'PRIMERA_OPCION') {
    //gestión de selección
    if (item.seleccionado) {
        const carreraSedeAspirante = item[opcion];
        if (carreraSedeAspirante) {
            //Tiene la opción buscar la carrera sede
            const index = carrerasSede.value.findIndex((cs) => cs.carrera_sede_id === carreraSedeAspirante.carrera_sede_id);

            if (index >= 0) {
                const carreraSede = carrerasSede.value[index];

                //verificar si tiene cupo
                let existeCupo = true;
                let mensaje = '';
                if (carreraSede.cupo > carreraSede.seleccionados) {
                    //Verificar si la convocatoria tiene cuota asignada por sector
                    if (convocatoria.value && convocatoria.value.configuracion && convocatoria.value.configuracion.cuota_sector_publico) {
                        const cupoSectorPublico = Math.ceil((carreraSede.cupo * convocatoria.value.configuracion.cuota_sector_publico) / 100);
                        const cupoSector = item.sector === 'Privado' ? carreraSede.cupo - cupoSectorPublico : cupoSectorPublico;
                        const seleccionadosSector = item.sector === 'Privado' ? carreraSede.seleccionados_privado : carreraSede.seleccionados_publico;
                        if (cupoSector <= seleccionadosSector) {
                            existeCupo = false;
                            mensaje = t('aspirante._no_tiene_cupo_sector_') + ' ' + item.sector;
                        }
                    }
                } else {
                    existeCupo = false;
                    mensaje = t('aspirante._no_tiene_cupo_');
                }
                if (existeCupo) {
                    carreraSede.seleccionados++;
                    infoSede.value.seleccionadosSede++;

                    item.solicitud_carrera_sede_id = carreraSedeAspirante.solicitud_carrera_sede_id;
                    item.carrera_sede_id = carreraSedeAspirante.carrera_sede_id;
                    if (item.sector === 'Privado') {
                        carreraSede.seleccionados_privado++;
                        infoSede.value.seleccionadosPrivadoSede++;
                    } else {
                        carreraSede.seleccionados_publico++;
                        infoSede.value.seleccionadosPublicoSede++;
                    }
                    ultimaSeleccion.value = carreraSede;
                } else if (!ejecutandoSeleccionAutomatica.value || (ejecutandoSeleccionAutomatica.value && tipoSeleccion.value.id == 2)) {
                    //sin cupo, verificar si hay cupo en las otras opciones
                    let siguienteOpcion = '';
                    if (opcion === 'PRIMERA_OPCION') {
                        //tiene segunda opción?
                        if (item.hasOwnProperty('SEGUNDA_OPCION')) {
                            siguienteOpcion = 'SEGUNDA_OPCION';
                        }
                    } else if (opcion === 'SEGUNDA_OPCION') {
                        //tiene tercera opción?
                        if (item.hasOwnProperty('TERCERA_OPCION')) {
                            siguienteOpcion = 'TERCERA_OPCION';
                        }
                    }

                    if (ejecutandoSeleccionAutomatica.value) {
                        seleccionar(item, siguienteOpcion);
                    } else {
                        if (siguienteOpcion != '') {
                            Swal.fire({
                                title: carreraSede.carrera + ' ' + mensaje,
                                text: '¿' + t('aspirante._verificar_carrera_en_') + ' ' + siguienteOpcion + '?',
                                showCancelButton: true,
                                confirmButtonText: t('_si_'),
                                cancelButtonText: t('_cancelar_'),
                                confirmButtonColor: '#81D4FA',
                                cancelButtonColor: '#D7E1EE',
                            }).then(async (result: any) => {
                                if (result.isConfirmed) {
                                    seleccionar(item, siguienteOpcion);
                                } else {
                                    item.seleccionado = false;
                                }
                            });
                        } else {
                            item.seleccionado = false;
                            text.value = t('aspirante._no_cupo_ninguna_carrera_elegida_aspirante_');
                            snackbar.value = true;
                        }
                    }
                } else {
                    item.seleccionado = false;
                }
            }
        }
    } else if (!ejecutandoSeleccionAutomatica.value) {
        //quitar la selección
        const index = carrerasSede.value.findIndex((cs) => cs.carrera_sede_id === item.carrera_sede_id);

        if (index >= 0) {
            const carreraSede = carrerasSede.value[index];
            console.log(carreraSede);
            carreraSede.seleccionados--;
            infoSede.value.seleccionadosSede--;

            if (item.sector === 'Privado') {
                carreraSede.seleccionados_privado--;
                infoSede.value.seleccionadosPrivadoSede--;
            } else {
                carreraSede.seleccionados_publico--;
                infoSede.value.seleccionadosPublicoSede--;
            }

            ultimaSeleccion.value = carreraSede;
        }

        item.solicitud_carrera_sede_id = null;
        item.carrera_sede_id = null;
    }

    if (!ejecutandoSeleccionAutomatica.value || (ejecutandoSeleccionAutomatica.value && item.seleccionado))
        axios
            .get(
                route('ingreso-solicitud-seleccion-aplicar', {
                    id: item.id,
                    seleccionado: item.seleccionado,
                    idSolicitudCarreraSede: item.solicitud_carrera_sede_id,
                }),
            )
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                // handle error
                console.error('Error fetching data:', error);
            });
}

function seleccionAutomatica() {
    console.log(tipoSeleccion);

    Swal.fire({
        title: t('convocatoria._ejecutar_seleccion_automatica_?'),
        text: tipoSeleccion.value.id == 1 ? t('convocatoria._seleccion_tipo1_') : t('convocatoria._seleccion_tipo2_'),
        showCancelButton: true,
        confirmButtonText: t('_si_'),
        cancelButtonText: t('_cancelar_'),
        confirmButtonColor: '#81D4FA',
        cancelButtonColor: '#D7E1EE',
    })
        .then(async (result: any) => {
            if (result.isConfirmed) {
                overlay.value = true;
                loading.value = true;
                ejecutandoSeleccionAutomatica.value = true;

                solicitudes.value.forEach((item) => {
                    //si no está seleccionado y aún hay cupo en alguna carrera de la sede
                    if (!item.seleccionado && infoSede.value.cupoSede > infoSede.value.seleccionadosSede) {
                        item.seleccionado = true;
                        seleccionar(item);
                    }
                });
            }
        })
        .finally(function () {
            loading.value = false;
            ejecutandoSeleccionAutomatica.value = false;
            overlay.value = false;
        });
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
            <v-layout>
                <v-app-bar :elevation="0" class="bg-blue-grey-lighten-3">
                    <v-app-bar-title>
                        <v-card class="mx-auto bg-transparent">
                            <template v-slot:title>
                                <span>{{ convocatoria?.descripcion }}</span>
                            </template>
                            <template v-slot:subtitle v-if="sede">
                                <span>SEDE: {{ sede?.nombre }}</span>
                                <span
                                    class="ms-3"
                                    v-if="convocatoria && convocatoria.configuracion && convocatoria.configuracion.cuota_sector_publico"
                                >
                                    ({{ $t('convocatoria._cuota_cupo_sector_') }}:
                                    <span class="text-pink font-weight-black"
                                        >{{ $t('convocatoria._publico_') }} - {{ convocatoria.configuracion.cuota_sector_publico }}%
                                    </span>
                                    ::
                                    <span class="text-pink font-weight-black">
                                        {{ $t('convocatoria._privado_') }} - {{ 100 - convocatoria.configuracion.cuota_sector_publico }}%
                                    </span>
                                    )
                                </span>
                            </template>
                        </v-card>
                    </v-app-bar-title>

                    <template v-slot:append>
                        <v-btn v-if="step == 1" icon="mdi-application-cog" @click.stop="drawer = !drawer" :title="$t('_parametros_')"></v-btn>
                        <v-btn v-if="step == 1" icon="mdi-chart-bar" @click.stop="step = 2" :title="$t('_grafico_')"></v-btn>

                        <v-btn v-if="step == 2" icon="mdi-format-list-checks" @click.stop="step = 1" :title="$t('_listado_seleccion_')"></v-btn>
                    </template>
                </v-app-bar>

                <v-window v-model="step" class="pt-8">
                    <v-window-item :value="1" class="pt-8">
                        <v-layout>
                            <v-navigation-drawer location="right" v-model="drawer" color="blue-grey-lighten-5" :width="400">
                                <v-row>
                                    <v-col cols="12">
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
                                                prepend-icon="mdi-reload"
                                                @click="cargarSolicitudes"
                                                :disabled="convocatoria == null || sede == null"
                                                >{{ $t('_cargar_') }}</v-btn
                                            >
                                        </v-row>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col class="mt-6">
                                        <v-expansion-panels v-if="solicitudes.length > 0">
                                            <v-expansion-panel :title="$t('convocatoria._seleccion_automatica_')">
                                                <v-expansion-panel-text>
                                                    <v-select
                                                        clearable
                                                        v-model="tipoSeleccion"
                                                        :label="$t('convocatoria._tipo_seleccion_')"
                                                        :items="[
                                                            { id: 1, title: 'Nota y primera opción' },
                                                            { id: 2, title: 'Nota, cualquier opción' },
                                                        ]"
                                                        item-title="title"
                                                        item-value="id"
                                                        return-object
                                                    ></v-select>

                                                    <span v-if="tipoSeleccion">
                                                        <span v-if="tipoSeleccion.id == 1"> {{ $t('convocatoria._seleccion_tipo1_') }}</span>
                                                        <span v-else> {{ $t('convocatoria._seleccion_tipo2_') }}</span>
                                                    </span>

                                                    <v-row justify="center" class="mt-6">
                                                        <v-btn
                                                            color="success"
                                                            :loading="ejecutandoSeleccionAutomatica"
                                                            rounded="xl"
                                                            variant="elevated"
                                                            prepend-icon="mdi-play-speed"
                                                            @click="seleccionAutomatica"
                                                            :disabled="tipoSeleccion == null"
                                                            >{{ $t('_ejecutar_') }}</v-btn
                                                        >
                                                    </v-row>
                                                </v-expansion-panel-text>
                                            </v-expansion-panel>
                                        </v-expansion-panels>
                                    </v-col>
                                </v-row>
                            </v-navigation-drawer>

                            <v-navigation-drawer location="right" permanent class="rounded-r-xl" width="350">
                                <v-list density="compact" nav>
                                    <v-card :title="$t('convocatoria._consolidado_sede_')" class="elevation-4 ma-2 border-1">
                                        <v-card-text>
                                            <span class="text-caption text-pink-darken-4" v-if="sede">SEDE: {{ sede.nombre }}</span>
                                            <v-container class="text-center">
                                                <v-progress-linear
                                                    :model-value="(infoSede.seleccionadosSede / infoSede.cupoSede) * 100"
                                                    height="30"
                                                    color="indigo-lighten-2"
                                                    class="border-md elevation-6 mb-2"
                                                    rounded
                                                >
                                                    <strong>
                                                        {{ $t('convocatoria._seleccionados_') }} {{ infoSede.seleccionadosSede }}/{{
                                                            infoSede.cupoSede
                                                        }}
                                                    </strong>
                                                </v-progress-linear>
                                                <v-pie
                                                    v-if="infoSede.seleccionadosSede > 0"
                                                    :gauge-cut="100"
                                                    hide-slice
                                                    :inner-cut="70"
                                                    animation
                                                    :palette="['#00876c', '#d43d51']"
                                                    :size="100"
                                                    reveal
                                                    :legend="{ position: 'right', textFormat: '[title] ([value]%)' }"
                                                    :items="[
                                                        {
                                                            key: 1,
                                                            title: $t('convocatoria._publico_'),
                                                            value:
                                                                infoSede.seleccionadosSede > 0
                                                                    ? Math.round(
                                                                          (infoSede.seleccionadosPublicoSede / infoSede.seleccionadosSede) * 100,
                                                                      )
                                                                    : 0,
                                                        },
                                                        {
                                                            key: 2,
                                                            title: $t('convocatoria._privado_'),
                                                            value:
                                                                infoSede.seleccionadosSede > 0
                                                                    ? Math.round(
                                                                          (infoSede.seleccionadosPrivadoSede / infoSede.seleccionadosSede) * 100,
                                                                      )
                                                                    : 0,
                                                        },
                                                    ]"
                                                >
                                                    <template #legend="{ items }">
                                                        <div class="d-flex flex-column">
                                                            <div v-for="(item, i) in items" :key="i" class="d-flex align-center">
                                                                <div
                                                                    :style="{ background: item.color, width: '12px', height: '12px' }"
                                                                    class="mr-2 rounded"
                                                                />
                                                                {{ item.title }} ({{ item.value }}%)
                                                            </div>
                                                        </div>
                                                    </template>
                                                </v-pie>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                    <v-divider></v-divider>
                                    <v-card
                                        v-if="ultimaSeleccion != null"
                                        :title="$t('aspirante._ultima_seleccion_')"
                                        class="elevation-4 ma-2 border-1"
                                    >
                                        <v-card-text>
                                            <span class="text-caption text-orange-darken-4">{{ ultimaSeleccion.carrera }}</span>
                                            <v-container class="text-center">
                                                <v-progress-linear
                                                    :model-value="(ultimaSeleccion.seleccionados / ultimaSeleccion.cupo) * 100"
                                                    height="30"
                                                    color="indigo-lighten-2"
                                                    class="border-md elevation-6 mb-2"
                                                    rounded
                                                >
                                                    <strong
                                                        >{{ $t('convocatoria._seleccionados_') }} {{ ultimaSeleccion.seleccionados }}/{{
                                                            ultimaSeleccion.cupo
                                                        }}
                                                    </strong>
                                                </v-progress-linear>
                                                <v-pie
                                                    v-if="ultimaSeleccion.seleccionados > 0"
                                                    :gauge-cut="100"
                                                    hide-slice
                                                    :inner-cut="70"
                                                    animation
                                                    :palette="['#00876c', '#d43d51']"
                                                    :size="100"
                                                    reveal
                                                    :legend="{ position: 'right', textFormat: '[title] ([value]%)' }"
                                                    :items="[
                                                        {
                                                            key: 1,
                                                            title: $t('convocatoria._publico_'),
                                                            value:
                                                                ultimaSeleccion.seleccionados > 0
                                                                    ? Math.round(
                                                                          (ultimaSeleccion.seleccionados_publico / ultimaSeleccion.seleccionados) *
                                                                              100,
                                                                      )
                                                                    : 0,
                                                        },
                                                        {
                                                            key: 2,
                                                            title: $t('convocatoria._privado_'),
                                                            value:
                                                                ultimaSeleccion.seleccionados > 0
                                                                    ? Math.round(
                                                                          (ultimaSeleccion.seleccionados_privado / ultimaSeleccion.seleccionados) *
                                                                              100,
                                                                      )
                                                                    : 0,
                                                        },
                                                    ]"
                                                >
                                                    <template #legend="{ items }">
                                                        <div class="d-flex flex-column">
                                                            <div v-for="(item, i) in items" :key="i" class="d-flex align-center">
                                                                <div
                                                                    :style="{ background: item.color, width: '12px', height: '12px' }"
                                                                    class="mr-2 rounded"
                                                                />
                                                                {{ item.title }} ({{ item.value }}%)
                                                            </div>
                                                        </div>
                                                    </template>
                                                </v-pie>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                    <v-divider></v-divider>
                                    <v-card v-for="cs in carrerasSede" :key="cs.id" class="elevation-4 ma-2 border-1">
                                        <v-card-text>
                                            <span class="text-caption text-green-darken-3">{{ cs.carrera }}</span>
                                            <v-container class="text-center">
                                                <v-progress-linear
                                                    rounded
                                                    :model-value="(cs.seleccionados / cs.cupo) * 100"
                                                    color="indigo-lighten-2"
                                                    height="30"
                                                    class="border-md elevation-6 mb-2"
                                                >
                                                    <strong>{{ $t('convocatoria._seleccionados_') }} {{ cs.seleccionados }}/{{ cs.cupo }} </strong>
                                                </v-progress-linear>
                                                <v-pie
                                                    v-if="cs.seleccionados > 0"
                                                    :gauge-cut="100"
                                                    hide-slice
                                                    :inner-cut="70"
                                                    animation
                                                    :palette="['#00876c', '#d43d51']"
                                                    :size="100"
                                                    reveal
                                                    :legend="{ position: 'right', textFormat: '[title] ([value]%)' }"
                                                    :items="[
                                                        {
                                                            key: 1,
                                                            title: $t('convocatoria._publico_'),
                                                            value:
                                                                cs.seleccionados > 0
                                                                    ? Math.round((cs.seleccionados_publico / cs.seleccionados) * 100)
                                                                    : 0,
                                                        },
                                                        {
                                                            key: 2,
                                                            title: $t('convocatoria._privado_'),
                                                            value:
                                                                cs.seleccionados > 0
                                                                    ? Math.round((cs.seleccionados_privado / cs.seleccionados) * 100)
                                                                    : 0,
                                                        },
                                                    ]"
                                                >
                                                    <template #legend="{ items }">
                                                        <div class="d-flex flex-column">
                                                            <div v-for="(item, i) in items" :key="i" class="d-flex align-center">
                                                                <div
                                                                    :style="{ background: item.color, width: '12px', height: '12px' }"
                                                                    class="mr-2 rounded"
                                                                />
                                                                {{ item.title }} ({{ item.value }}%)
                                                            </div>
                                                        </div>
                                                    </template>
                                                </v-pie>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-list>
                            </v-navigation-drawer>
                            <v-main class="w-100 overflow-y-scroll rounded-l-xl text-4xl" style="height: 85dvh">
                                <v-card-title class="d-flex align-center border-b-md pe-2">
                                    <v-spacer></v-spacer>

                                    <v-text-field
                                        v-model="search"
                                        density="compact"
                                        :label="$t('_buscar_')"
                                        prepend-inner-icon="mdi-magnify"
                                        variant="outlined"
                                        rounded="xl"
                                        flat
                                        hide-details
                                        single-line
                                    ></v-text-field>
                                </v-card-title>
                                <v-data-table
                                    v-model:search="search"
                                    :headers="headers"
                                    :items="solicitudes"
                                    :sort-by="sortBy"
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
                                                <v-checkbox-btn
                                                    v-model="item.seleccionado"
                                                    :ripple="false"
                                                    @update:modelValue="seleccionar(item)"
                                                ></v-checkbox-btn>
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
                    </v-window-item>
                    <v-window-item :value="2" class="pt-8"> Un grafiquito </v-window-item>
                </v-window>
            </v-layout>
        </v-card>
    </AppLayout>
    <v-snackbar v-model="snackbar" multi-line>
        {{ text }}

        <template v-slot:actions>
            <v-btn color="white" @click="snackbar = false" icon="mdi-close-circle-outline"></v-btn>
        </template>
    </v-snackbar>
    <v-overlay :model-value="overlay" class="align-center justify-center" persistent>
        <v-progress-circular color="primary" size="64" indeterminate></v-progress-circular>
    </v-overlay>
</template>
