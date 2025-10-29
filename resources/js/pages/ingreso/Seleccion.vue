<script setup lang="ts">
import SeleccionAppBar from '@/components/ingreso/SeleccionAppBar.vue';
import SeleccionDrawerParametros from '@/components/ingreso/SeleccionDrawerParametros.vue';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { CarreraSede, Convocatoria, InfoSede, Sede, Solicitud, SortBy } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { PropType, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();

const { t } = useI18n();

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
const solicitudesEnProceso = ref(0);
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
                        if (siguienteOpcion != '') {
                            seleccionar(item, siguienteOpcion);
                        } else {
                            item.seleccionado = false;
                        }
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

    if (
        !ejecutandoSeleccionAutomatica.value ||
        (ejecutandoSeleccionAutomatica.value && item.seleccionado && item.solicitud_carrera_sede_id != null)
    ) {
        solicitudesEnProceso.value++;
        axios
            .get(
                route('ingreso-solicitud-seleccion-aplicar', {
                    id: item.id,
                    seleccionado: item.seleccionado,
                    idSolicitudCarreraSede: item.solicitud_carrera_sede_id,
                }),
            )
            .then(function (response) {
                //console.log(response);
            })
            .catch(function (error) {
                // handle error
                console.error('Error fetching data:', error);
            })
            .finally(function () {
                solicitudesEnProceso.value--;
            });
    }
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

watch(solicitudesEnProceso, () => {
    if (solicitudesEnProceso.value > 1) {
        overlay.value = true;
    } else {
        overlay.value = false;
    }
});

const handleChangeStep = (newStep: number) => {
    step.value = newStep;
};

const handleChangeDrawer = () => {
    drawer.value = !drawer.value;
};

const handleChangeConvocatoria = (newConvocatoria: Convocatoria) => {
    convocatoria.value = newConvocatoria;
};

const handleChangeSede = (newSede: Sede) => {
    sede.value = newSede;
};

const handleChangeTipoSeleccion = (newTipoSeleccion: any) => {
    tipoSeleccion.value = newTipoSeleccion;
};

const handleSolicitudes = (newSolicitudes: []) => {
    solicitudes.value = newSolicitudes;
};

const handleCarrerasSede = (newCarrerasSede: []) => {
    carrerasSede.value = newCarrerasSede;
};

const handleInfoSede = (newInfoSede: any) => {
    infoSede.value = newInfoSede;
};
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
                <SeleccionAppBar
                    :convocatoria="convocatoria"
                    :sede="sede"
                    :step="step"
                    @change-step="handleChangeStep"
                    @change-drawer="handleChangeDrawer"
                >
                </SeleccionAppBar>
                <SeleccionDrawerParametros
                    :convocatorias="props.convocatorias"
                    :drawer="drawer"
                    @change-drawer="handleChangeDrawer"
                    @change-convocatoria="handleChangeConvocatoria"
                    @change-sede="handleChangeSede"
                    @change-tipo-seleccion="handleChangeTipoSeleccion"
                    @solicitudes="handleSolicitudes"
                    @carreras-sede="handleCarrerasSede"
                    @info-sede="handleInfoSede"
                    @seleccion-automatica="seleccionAutomatica"
                >
                </SeleccionDrawerParametros>

                <v-window v-model="step" class="pt-8">
                    <v-window-item :value="1" class="pt-8">
                        <v-layaout>
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
                        </v-layaout>
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
        <v-card class="pa-6" rounded="xl">
            <v-progress-linear color="deep-purple-accent-4" indeterminate rounded height="20"></v-progress-linear>
            <v-divider></v-divider>
            <span class="text-h6"> {{ $t('convocatoria._solicitudes_pendientes_procesar_') }}: </span>
            <span class="text-h4 text-red-accent-3 ms-4">{{ solicitudesEnProceso }}</span>
        </v-card>
    </v-overlay>
</template>
