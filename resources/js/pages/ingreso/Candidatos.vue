<script setup lang="ts">
import GraficoCandidatos from '@/components/ingreso/GraficoCandidatos.vue';
import Invitaciones from '@/components/ingreso/Invitaciones.vue';
import ListadoCandidatos from '@/components/ingreso/ListadoCandidatos.vue';
import { useFunciones } from '@/composables/useFunciones';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();

const { t } = useI18n();
const { mensajeError } = useFunciones();

interface Departamento {
    codigo_depto: string;
    departamento: string;
}
interface Convocatoria {
    id: number;
    nombre: string;
    descripcion: string;
    invitaciones: number;
    solicitud: object | null;
    invitaciones_pendientes_envio: number;
    invitaciones_aceptadas: number;
}
interface Props {
    departamentos?: Departamento[];
    opcionesBachillerato?: [];
    convocatorias: Convocatoria[];
}

const convocatoria = ref<Convocatoria | null>(null);

const dialog = ref(false);
const noData = ref(false);
const props = defineProps<Props>();

const localConvocatorias = ref([...props.convocatorias]);

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

function enviarInvitacionesPendientes() {
    if (convocatoria.value?.invitaciones_pendientes_envio > 0) {
        const hasError = ref(false);
        const message = ref('');
        const messageLog = ref('');
        Swal.fire({
            title: t('invitacion._confirmar_enviar_pendientes_'),
            text: props.selectedItemLabel,
            showCancelButton: true,
            confirmButtonText: t('invitacion._enviar_'),
            cancelButtonText: t('_cancelar_'),
            confirmButtonColor: '#e5adac',
            cancelButtonColor: '#D7E1EE',
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const resp = await axios.get(route('ingreso-convocatoria-invitaciones-pendientes', { id: convocatoria.value?.id }));

                    if (resp.data.status == 'ok') {
                        handleConvocatoria(resp.data.convocatoria);
                    } else {
                        hasError.value = true;
                        message.value = t(resp.data.message);
                    }
                } catch (error: any) {
                    hasError.value = true;
                    messageLog.value = error.response.data.message;
                }

                if (hasError.value) {
                    console.log(messageLog.value);
                    mensajeError(t('invitacion._no_se_pudo_realizar_envio_') + '. ' + message.value);
                }
            }
        });
    }
}

function handleConvocatoria(newConvocatoria: Convocatoria) {
    const index = localConvocatorias.value.findIndex((item) => item.id === newConvocatoria.value?.id);
    localConvocatorias.value[index] = newConvocatoria;
    convocatoria.value = newConvocatoria;
}
</script>

<template>
    <Head :title="$t('_candidatos_')"></Head>

    <AppLayout :titulo="$t('_candidatos_')" :subtitulo="$t('_estudiantes_candidatos_carrera_universitaria_')" icono="mdi-account-star-outline">
        <v-sheet v-if="hasPermission('MENU_INGRESO_CONVOCATORIA_CANDIDATOS')" class="elevation-12 pa-2 rounded-xl">
            <v-alert
                border="top"
                type="warning"
                variant="outlined"
                prominent
                v-if="convocatoria && convocatoria.solicitud && convocatoria.solicitud.etapa.codigo != 'INVITACIONES'"
            >
                {{ $t('convocatoria._alerta_invitaciones_') }}
            </v-alert>
            <div class="d-flex flex-row">
                <v-alert
                    v-if="convocatoria != null"
                    :title="$t('convocatoria._convocatoria_seleccionada_')"
                    type="info"
                    border="top"
                    prominent
                    variant="outlined"
                >
                    <div>{{ convocatoria?.nombre }}</div>
                    <div>{{ convocatoria?.descripcion }}</div>

                    <v-divider :thickness="4"></v-divider>
                    <v-btn size="x-small" variant="outlined" @click="dialog = true" :title="$t('_cambiar_convocatoria_')">{{
                        $t('_cambiar_')
                    }}</v-btn>
                    <template v-slot:append>
                        <v-btn stacked :title="$t('invitacion._invitaciones_creadas_')">
                            <template v-slot:prepend>
                                <v-badge :offset-x="-10" location="top right" color="primary" :content="convocatoria?.invitaciones">
                                    <v-icon color="primary" icon="mdi-email-newsletter"></v-icon>
                                </v-badge>
                            </template>
                            {{ $t('invitacion._creadas_') }}
                        </v-btn>
                        <v-btn stacked :title="$t('invitacion._invitaciones_pendientes_')" @click="enviarInvitacionesPendientes">
                            <template v-slot:prepend>
                                <v-badge :offset-x="-10" location="top right" color="warning" :content="convocatoria?.invitaciones_pendientes_envio">
                                    <v-icon color="warning" icon="mdi-email-alert-outline"></v-icon>
                                </v-badge>
                            </template>
                            <span v-if="convocatoria?.invitaciones_pendientes_envio > 0">{{ $t('invitacion._pendientes_') }}...</span>
                            <span v-else>{{ $t('invitacion._pendientes_') }}</span>
                        </v-btn>
                        <v-btn stacked :title="$t('invitacion._invitaciones_aceptadas_')">
                            <template v-slot:prepend>
                                <v-badge :offset-x="-10" location="top right" color="success" :content="convocatoria?.invitaciones_aceptadas">
                                    <v-icon color="success" icon="mdi-email-check-outline"></v-icon>
                                </v-badge>
                            </template>
                            {{ $t('invitacion._aceptadas_') }}
                        </v-btn>
                    </template>
                </v-alert>
            </div>
            <v-alert border="top" type="warning" variant="outlined" prominent v-if="noData">
                {{ $t('convocatoria._alerta_invitaciones_no_data_') }}
            </v-alert>
            <div class="d-flex flex-row" v-else>
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
                            <v-card-text> <GraficoCandidatos @no-data="noData = true" /> </v-card-text>
                        </v-card>
                    </v-tabs-window-item>

                    <v-tabs-window-item v-if="hasPermission('INGRESO_CONVOCATORIA_CANDIDATOS_LISTADO')" value="option-2">
                        <v-card flat>
                            <v-card-text v-if="convocatoria != null">
                                <ListadoCandidatos
                                    @convocatoria="handleConvocatoria"
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
                                <Invitaciones :convocatoria="convocatoria" @convocatoria="handleConvocatoria" />
                            </v-card-text>
                        </v-card>
                    </v-tabs-window-item>
                </v-tabs-window>
            </div>
            <v-dialog v-model="dialog" max-width="400" persistent>
                <v-card>
                    <v-card-text class="bg-surface-light pt-4">
                        <v-autocomplete
                            :label="$t('convocatoria._convocatoria_')"
                            :items="localConvocatorias"
                            :hint="$t('convocatoria._convocatoria_a_utilizar_para_enviar_invitaciones_')"
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
        </v-sheet>
    </AppLayout>
</template>
