<script setup lang="ts">
import SeleccionAppBar from '@/components/ingreso/SeleccionAppBar.vue';
import SeleccionDrawerGraficos from '@/components/ingreso/SeleccionDrawerGraficos.vue';
import SeleccionDrawerParametros from '@/components/ingreso/SeleccionDrawerParametros.vue';
import SeleccionGrafico from '@/components/ingreso/SeleccionGrafico.vue';
import SeleccionListado from '@/components/ingreso/SeleccionListado.vue';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { CarreraSede, Convocatoria, InfoSede, Sede, Solicitud } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { computed, PropType, ref, watch } from 'vue';
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
const infoSede = ref<InfoSede[]>([]);
const carrerasSede = ref<CarreraSede[]>([]);
const ultimaSeleccion = ref<CarreraSede | null>(null);
const solicitudes = ref<Solicitud[]>([]);
const tipoSeleccion = ref(null);
const snackbar = ref(false);
const overlay = ref(false);
const solicitudesEnProceso = ref(0);
const text = ref('');
const step = ref(1);

const drawer = ref(true);

const seleccionados = computed(() => {
    return solicitudes.value.filter((sol: Solicitud) => sol.seleccionado);
});

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
                    infoSede.value[item.sede].seleccionados++;

                    item.solicitud_carrera_sede_id = carreraSedeAspirante.solicitud_carrera_sede_id;
                    item.carrera_sede_id = carreraSedeAspirante.carrera_sede_id;
                    if (item.sector === 'Privado') {
                        carreraSede.seleccionados_privado++;
                        infoSede.value[item.sede].seleccionados_privado++;
                    } else {
                        carreraSede.seleccionados_publico++;
                        infoSede.value[item.sede].seleccionados_publico++;
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
            infoSede.value[item.sede].seleccionados--;

            if (item.sector === 'Privado') {
                carreraSede.seleccionados_privado--;
                infoSede.value[item.sede].seleccionados_privado--;
            } else {
                carreraSede.seleccionados_publico--;
                infoSede.value[item.sede].seleccionados_publico--;
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
                    if (!item.seleccionado && infoSede.value[item.sede].cupo > infoSede.value[item.sede].seleccionados) {
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
    if (newStep == 2) {
        drawer.value = false;
    }
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
                    :solicitudes="solicitudes"
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

                <v-window v-model="step">
                    <v-window-item :value="1">
                        <v-layaout>
                            <SeleccionDrawerGraficos
                                :info-sede="infoSede"
                                :sede="sede"
                                :carreras-sede="carrerasSede"
                                :ultima-seleccion="ultimaSeleccion"
                            ></SeleccionDrawerGraficos>
                            <v-main class="w-100 overflow-y-scroll rounded-l-xl text-4xl" style="height: 91dvh">
                                <SeleccionListado :solicitudes="solicitudes" @seleccionar="seleccionar"> </SeleccionListado>
                            </v-main>
                        </v-layaout>
                    </v-window-item>
                    <v-window-item :value="2" class="pt-16" style="height: 91dvh">
                        <SeleccionGrafico :seleccionados="seleccionados"> </SeleccionGrafico>
                    </v-window-item>
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
