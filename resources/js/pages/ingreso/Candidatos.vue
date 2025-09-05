<script setup lang="ts">
import GraficoCandidatos from '@/components/ingreso/GraficoCandidatos.vue';
import Invitaciones from '@/components/ingreso/Invitaciones.vue';
import ListadoCandidatos from '@/components/ingreso/ListadoCandidatos.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Departamento {
    codigo_depto: string;
    departamento: string;
}
interface Props {
    departamentos?: Departamento[];
    opcionesBachillerato?: [];
}

const props = defineProps<Props>();

const tab = ref('option-1');
</script>

<template>
    <Head :title="$t('_candidatos_')"></Head>
    <AppLayout :titulo="$t('_candidatos_')" :subtitulo="$t('_estudiantes_candidatos_carrera_universitaria_')" icono="mdi-email-fast-outline">
        <div class="d-flex flex-row">
            <v-tabs v-model="tab" color="primary" direction="vertical">
                <v-tab prepend-icon="mdi-chart-bar" :text="$t('_resumen_')" value="option-1"></v-tab>
                <v-tab prepend-icon="mdi-list-status" :text="$t('_listado_')" value="option-2"></v-tab>
                <v-tab prepend-icon="mdi-email-fast-outline" :text="$t('_invitaciones_')" value="option-3"></v-tab>
            </v-tabs>

            <v-tabs-window v-model="tab" class="w-full">
                <v-tabs-window-item value="option-1">
                    <v-card flat>
                        <v-card-text> <GraficoCandidatos /> </v-card-text>
                    </v-card>
                </v-tabs-window-item>

                <v-tabs-window-item value="option-2">
                    <v-card flat>
                        <v-card-text>
                            <ListadoCandidatos :departamentos="departamentos" :opcionesBachillerato="opcionesBachillerato"></ListadoCandidatos>
                        </v-card-text>
                    </v-card>
                </v-tabs-window-item>
                <v-tabs-window-item value="option-3">
                    <v-card flat>
                        <v-card-text>
                            <Invitaciones />
                        </v-card-text>
                    </v-card>
                </v-tabs-window-item>
            </v-tabs-window>
        </div>
    </AppLayout>
</template>
