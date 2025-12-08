<script setup lang="ts">
import InscritosDatos from '@/components/reportes/academico/InscritosDatos.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
    semestre: Object,
    sede: Array,
    carrera: Array,
    unidadAcademica: Array,
});

const config = computed(() => {
    return {
        titulo1: t('reporte._inscritos_'),
        titulo2: t('reporte._inscritos_'),
        subtitulo: props.unidadAcademica[0]?.nombreCompleto,
        icono: 'mdi-playlist-check',
    };
});
</script>

<template>
    <Head :title="config.titulo1"> </Head>
    <AppLayout :titulo="config.titulo2" :subtitulo="config.subtitulo" :icono="config.icono">
        <InscritosDatos
            :semestre="props.semestre"
            :sedeSeleccion="props.sede"
            :carreraSeleccion="props.carrera"
            :unidadAcademicaSeleccion="props.unidadAcademica"
            :step="2"
            :botonAtras="false"
        />
    </AppLayout>
</template>
