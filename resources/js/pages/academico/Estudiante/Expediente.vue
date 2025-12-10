<script setup lang="ts">
import ExpedienteDatos from '@/components/academico/estudiante/ExpedienteDatos.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { CarreraSede, CarreraUnidadAcademica, Estudiante, Expediente } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const tab = ref(null);

interface FormData {
    carga_inscrita: Array<[]>;
}

const formData = ref<FormData>({
    carga_inscrita: [],
});

const props = defineProps({
    expediente: {
        type: Array as PropType<Expediente[]>,
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
    mallaCurricular: {
        type: Array as PropType<CarreraUnidadAcademica[]>,
        required: true,
        default: () => [],
    },
});

//computed expdiente aprobadas
const expedienteAprobadas = computed(() => {
    return props.expediente.filter((item) => item.estado.codigo === 'AP');
});

// expediente en curso
const expedienteEnCurso = computed(() => {
    return props.expediente.filter((item) => item.estado.codigo === 'EC');
});

const seleccionado = ref(null);
</script>

<template>
    <Head :title="$t('expediente._singular_')"></Head>
    <AppLayout :titulo="$t('expediente._singular_')" :subtitulo="$t('expediente._titulo_descripcion_')" icono="mdi-folder-table-outline">
        <v-card>
            <ExpedienteDatos
                :expediente="props.expediente"
                :estudiante="props.estudiante"
                :carreraSede="props.carreraSede"
                :mallaCurricular="props.mallaCurricular"
            />
        </v-card>
    </AppLayout>
</template>
