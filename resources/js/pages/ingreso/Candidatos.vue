<script setup lang="ts">
import GraficoCandidatos from '@/components/ingreso/GraficoCandidatos.vue';
import Invitaciones from '@/components/ingreso/Invitaciones.vue';
import ListadoCandidatos from '@/components/ingreso/ListadoCandidatos.vue';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const { hasPermission } = usePermissions();

interface Departamento {
    codigo_depto: string;
    departamento: string;
}
interface Convocatoria {
    id: number;
    nombre: string;
    descripcion: string;
}
interface Props {
    departamentos?: Departamento[];
    opcionesBachillerato?: [];
    convocatorias: Convocatoria[];
}

const convocatoria = ref<Convocatoria | null>(null);

const dialog = ref(false);

const props = defineProps<Props>();

const tab = ref('option-1');

watch(
    () => tab.value,
    (newValue) => {
        if (newValue != 'option-1') {
            if (convocatoria.value === null) {
                dialog.value = true;
            }
        }
    },
);
</script>

<template>
    <Head :title="$t('_candidatos_')"></Head>

    <AppLayout :titulo="$t('_candidatos_')" :subtitulo="$t('_estudiantes_candidatos_carrera_universitaria_')" icono="mdi-email-fast-outline">
        <div v-if="hasPermission('MENU_INGRESO_CONVOCATORIA_CANDIDATOS')">
            <div class="d-flex flex-row">
                <v-alert v-if="convocatoria != null" :title="$t('_convocatoria_seleccionada_')" type="info" border="top" prominent variant="outlined">
                    <div>{{ convocatoria?.nombre }}</div>
                    <div>{{ convocatoria?.descripcion }}</div>

                    <v-divider :thickness="4"></v-divider>
                    <v-btn size="x-small" variant="outlined" @click="dialog = true" :title="$t('_cambiar_convocatoria_')">{{
                        $t('_cambiar_')
                    }}</v-btn>
                </v-alert>
            </div>
            <div class="d-flex flex-row">
                <v-tabs v-model="tab" color="primary" direction="vertical">
                    <v-tab prepend-icon="mdi-chart-bar" :text="$t('_resumen_')" value="option-1"></v-tab>
                    <v-tab
                        v-if="hasPermission('INGRESO_CONVOCATORIA_CANDIDATOS_LISTADO')"
                        prepend-icon="mdi-list-status"
                        :text="$t('_listado_')"
                        value="option-2"
                    ></v-tab>
                    <v-tab
                        v-if="hasPermission('INGRESO_CONVOCATORIA_CANDIDATOS_INVITACIONES')"
                        prepend-icon="mdi-email-fast-outline"
                        :text="$t('_invitaciones_')"
                        value="option-3"
                    ></v-tab>
                </v-tabs>

                <v-tabs-window v-model="tab" class="w-full">
                    <v-tabs-window-item value="option-1">
                        <v-card flat>
                            <v-card-text> <GraficoCandidatos /> </v-card-text>
                        </v-card>
                    </v-tabs-window-item>

                    <v-tabs-window-item v-if="hasPermission('INGRESO_CONVOCATORIA_CANDIDATOS_LISTADO')" value="option-2">
                        <v-card flat>
                            <v-card-text v-if="convocatoria != null">
                                <ListadoCandidatos
                                    :convocatoria="convocatoria"
                                    :departamentos="departamentos"
                                    :opcionesBachillerato="opcionesBachillerato"
                                ></ListadoCandidatos>
                            </v-card-text>
                        </v-card>
                    </v-tabs-window-item>
                    <v-tabs-window-item v-if="hasPermission('INGRESO_CONVOCATORIA_CANDIDATOS_INVITACIONES')" value="option-3">
                        <v-card flat>
                            <v-card-text v-if="convocatoria != null">
                                <Invitaciones :convocatoria="convocatoria" />
                            </v-card-text>
                        </v-card>
                    </v-tabs-window-item>
                </v-tabs-window>
            </div>
            <v-dialog v-model="dialog" max-width="400" persistent>
                <v-card>
                    <v-card-text class="bg-surface-light pt-4">
                        <v-autocomplete
                            :label="$t('_convocatoria_')"
                            :items="props.convocatorias"
                            :hint="$t('_convocatoria_a_utilizar_para_enviar_invitaciones_')"
                            persistent-hint
                            item-title="nombre"
                            item-value="id"
                            return-object
                            v-model="convocatoria"
                        ></v-autocomplete>
                    </v-card-text>
                    <template v-slot:actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="dialog = false" rounded variant="tonal" color="blue-darken-4"> {{ $t('_aceptar_') }} </v-btn>
                    </template>
                </v-card>
            </v-dialog>
        </div>
    </AppLayout>
</template>
