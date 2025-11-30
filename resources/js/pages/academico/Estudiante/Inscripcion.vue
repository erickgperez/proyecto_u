<script setup lang="ts">
import AsignaturasEnCurso from '@/components/academico/estudiante/AsignaturasEnCurso.vue';
import { useFunciones } from '@/composables/useFunciones';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { CarreraSede, Estudiante, Expediente, Oferta, Semestre } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { VForm } from 'vuetify/components';

const { hasPermission } = usePermissions();
const { rules, mensajeExito, mensajeError } = useFunciones();

const { t } = useI18n();
const loading = ref(false);
const formRef = ref<VForm | null>(null);

interface FormData {
    carga_inscrita: Array<[]>;
}

const formData = ref<FormData>({
    carga_inscrita: [],
});

const props = defineProps({
    semestre: {
        type: Object as PropType<Semestre>,
        required: true,
    },
    cargaAcademica: {
        type: Array as PropType<Oferta[]>,
        required: true,
        default: () => [],
    },
    estudiante: {
        type: Object as PropType<Estudiante>,
        required: true,
    },
    carreraSede: {
        type: Object as PropType<CarreraSede>,
        required: true,
    },
});

const expediente = ref<Expediente[]>(props.estudiante.expediente);
const localCargaAcademica = ref<Oferta[]>([]);

const cargaSeleccionada = ref([]);

async function submitForm() {
    loading.value = true;
    formData.value.carga_inscrita = cargaSeleccionada.value.map((objeto: Oferta) => {
        return {
            carrera_unidad_academica_id: objeto.carrera_unidad_academica_id,
            matricula: objeto.matricula,
        };
    });
    if (formData.value.carga_inscrita.length > 0) {
        try {
            const resp = await axios.post(
                route('academico-estudiante-inscripcion-save', {
                    uuid: props.estudiante.uuid,
                    uuidSemestre: props.semestre.uuid,
                    id: props.carreraSede.id,
                }),
                formData.value,
            );
            if (resp.data.status == 'ok') {
                mensajeExito(t('inscripcion._inscripcion_realizada_correctamente_'));
                expediente.value = resp.data.estudiante.expediente;
                localCargaAcademica.value = resp.data.cargaAcademica;
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
            mensajeError(t('inscripcion._no_se_pudo_guardar_inscripcion_') + '. ' + msj);
        }
    } else {
        mensajeError(t('inscripcion._seleccione_al_menos_una_asignatura_'));
    }
    loading.value = false;
}

onMounted(() => {
    localCargaAcademica.value = props.cargaAcademica;
});
</script>

<template>
    <Head :title="$t('inscripcion._singular_')"></Head>
    <AppLayout
        :titulo="$t('inscripcion._inscripcion_asignaturas_')"
        :subtitulo="$t('semestre._singular_') + ' ' + (semestre ? semestre.codigo : '')"
        icono="mdi-note-plus-outline"
    >
        <v-card class="mx-auto" rounded="xl">
            <v-toolbar color="blue-grey-lighten-1">
                <v-toolbar-title>{{ $t('inscripcion._carga_academica_') }} {{ carreraSede.titulo2 }}</v-toolbar-title>
            </v-toolbar>
            <v-divider></v-divider>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef" v-if="localCargaAcademica.length > 0">
                <v-list v-model:selected="cargaSeleccionada" lines="three" select-strategy="leaf" active-class="text-green-darken-2">
                    <v-list-subheader class="text-primary">{{ $t('inscripcion._inscripcion_asignaturas_indicacion_') }}</v-list-subheader>

                    <v-list-item v-for="item in localCargaAcademica" :key="item.id" :value="item">
                        <v-list-item-title>{{ item.carrera_unidad_academica.unidad_academica.nombre }}</v-list-item-title>

                        <v-list-item-subtitle class="text-high-emphasis">
                            {{ $t('_codigo_') }} {{ item.carrera_unidad_academica.unidad_academica.codigo }}
                        </v-list-item-subtitle>

                        <v-list-item-subtitle class="text-high-emphasis mb-1 opacity-100">
                            {{ $t('inscripcion._matricula_') + ' ' + item.matricula }}
                        </v-list-item-subtitle>

                        <template v-slot:prepend="{ isSelected, select }">
                            <v-list-item-action start>
                                <v-checkbox-btn :model-value="isSelected" @update:model-value="select"></v-checkbox-btn>
                            </v-list-item-action>
                        </template>
                    </v-list-item>
                </v-list>

                <v-card-actions v-if="localCargaAcademica.length > 0">
                    <v-spacer></v-spacer>
                    <v-btn type="submit" rounded variant="tonal" color="blue-darken-4" :loading="loading" prepend-icon="mdi-note-plus">
                        {{ $t('inscripcion._inscribir_') }}
                    </v-btn>
                </v-card-actions>
            </v-form>
            <v-alert v-else type="info" variant="outlined" border="start" :title="$t('inscripcion._no_hay_asignaturas_disponibles_')"></v-alert>
        </v-card>
        <v-divider class="my-4"></v-divider>

        <AsignaturasEnCurso :expediente="expediente" />
    </AppLayout>
</template>
