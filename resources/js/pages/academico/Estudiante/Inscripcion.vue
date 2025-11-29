<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { CarreraSede, Estudiante, Oferta, Semestre } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();

const { t } = useI18n();

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

const userControls = [
    { title: 'Content filtering', subtitle: 'Set the content filtering level to restrict appts that can be downloaded' },
    { title: 'Password', subtitle: 'Require password for purchase or use password to restrict purchase' },
];

const settingsItems = [
    { value: 'notifications', title: 'Notifications', subtitle: 'Notify me about updates to apps or games that I downloaded' },
    { value: 'sound', title: 'Sound', subtitle: 'Auto-update apps at any time. Data charges may apply' },
    { value: 'widgets', title: 'Auto-add widgets', subtitle: 'Automatically add home screen widgets when downloads complete' },
];

const cargaAcademicaSeleccionada = ref([]);
</script>

<template>
    <Head :title="$t('inscripcion._singular_')"></Head>
    <AppLayout
        :titulo="$t('inscripcion._inscripcion_asignaturas_')"
        :subtitulo="$t('semestre._singular_') + ' ' + semestre.codigo"
        icono="mdi-note-plus-outline"
    >
        <v-card class="mx-auto" rounded="xl">
            <v-toolbar color="blue-grey-lighten-1">
                <v-toolbar-title>{{ $t('inscripcion._carga_academica_') }} {{ carreraSede.titulo2 }}</v-toolbar-title>
            </v-toolbar>

            <v-divider></v-divider>

            <v-list v-model:selected="cargaAcademicaSeleccionada" lines="three" select-strategy="leaf" active-class="text-green-darken-2">
                <v-list-subheader>{{ $t('inscripcion._inscripcion_asignaturas_indicacion_') }}</v-list-subheader>

                <v-list-item v-for="item in cargaAcademica" :key="item.id" :value="item">
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
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" variant="tonal" @click="guardarInscripcion" prepend-icon="mdi-note-plus">
                    {{ $t('inscripcion._inscribir_') }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </AppLayout>
</template>
