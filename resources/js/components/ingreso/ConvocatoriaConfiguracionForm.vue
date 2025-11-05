<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);
const showMenu = ref(false);
const showMenu1 = ref(false);
const showMenu2 = ref(false);

const emit = defineEmits(['form-saved']);

function reset() {
    formRef.value!.reset();
}

interface FormData {
    id: number | null;
    fecha_publicacion_resultados: Date | null;
    hora_publicacion_resultados: string | null;
    fecha_inicio_recepcion_solicitudes: Date | null;
    hora_inicio_recepcion_solicitudes: string | null;
    fecha_fin_recepcion_solicitudes: Date | null;
    hora_fin_recepcion_solicitudes: string | null;
    cuota_sector_publico: number | null;
    prueba_bachillerato_id: number | null;
}

const props = defineProps(['item', 'accion', 'pruebasBachillerato']);

const formData = ref<FormData>({
    id: null,
    fecha_publicacion_resultados: null,
    hora_publicacion_resultados: '',
    fecha_inicio_recepcion_solicitudes: null,
    hora_inicio_recepcion_solicitudes: '',
    fecha_fin_recepcion_solicitudes: null,
    hora_fin_recepcion_solicitudes: '',
    cuota_sector_publico: null,
    prueba_bachillerato_id: null,
});

const dateFields = ['fecha_publicacion_resultados', 'fecha_inicio_recepcion_solicitudes', 'fecha_fin_recepcion_solicitudes'];
const isEditing = toRef(() => props.accion === 'configuracion');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.postForm(route('ingreso-convocatoria-configuracion-save', { id: props.item.id }), formData.value, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.convocatoria);
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + error.message);
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();
    if (props.item.configuracion != null) {
        formData.value = { ...props.item.configuracion };
        dateFields.forEach((field) => {
            if (formData.value[field]) {
                const fecha = new Date(formData.value[field]);
                const horaKey = field.replace('fecha_', 'hora_');
                const horas = fecha.getHours().toString().padStart(2, '0');
                const minutos = fecha.getMinutes().toString().padStart(2, '0');
                formData.value[horaKey] = `${horas}:${minutos}`;
            }
        });
    }
});
</script>
<template>
    <v-card :title="item.descripcion">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12" class="pt-15 pl-15">
                        <v-card class="mx-auto">
                            <v-toolbar class="bg-blue-grey-lighten-3">
                                <v-toolbar-title class="text-h6" :text="$t('convocatoria._configuracion_')"></v-toolbar-title>
                            </v-toolbar>

                            <v-card-text>
                                <v-row>
                                    <v-col cols="6">
                                        <v-locale-provider locale="es">
                                            <v-date-input
                                                clearable
                                                required
                                                :rules="[rules.required]"
                                                icon-color="deep-orange"
                                                v-model="formData.fecha_inicio_recepcion_solicitudes"
                                                :label="$t('convocatoria._fecha_inicio_recepcion_solicitudes_')"
                                                :hint="$t('convocatoria._fecha_inicio_recepcion_solicitudes_hint_')"
                                                persistent-hint
                                            ></v-date-input>
                                        </v-locale-provider>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field
                                            :model-value="formData.hora_inicio_recepcion_solicitudes"
                                            :label="$t('convocatoria._hora_inicio_recepcion_solicitudes_')"
                                            prepend-icon="mdi-clock-time-four-outline"
                                            readonly
                                            clearable
                                        >
                                            <v-menu v-model="showMenu1" :close-on-content-click="false" activator="parent" min-width="0">
                                                <v-time-picker v-model="formData.hora_inicio_recepcion_solicitudes" color="green"></v-time-picker>
                                            </v-menu>
                                        </v-text-field>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col cols="6">
                                        <v-locale-provider locale="es">
                                            <v-date-input
                                                required
                                                :rules="[rules.required]"
                                                icon-color="deep-orange"
                                                clearable
                                                v-model="formData.fecha_fin_recepcion_solicitudes"
                                                :label="$t('convocatoria._fecha_fin_recepcion_solicitudes_')"
                                                :hint="$t('convocatoria._fecha_fin_recepcion_solicitudes_hint_')"
                                                persistent-hint
                                            ></v-date-input>
                                        </v-locale-provider>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field
                                            :model-value="formData.hora_fin_recepcion_solicitudes"
                                            :label="$t('convocatoria._hora_fin_recepcion_solicitudes_')"
                                            prepend-icon="mdi-clock-time-four-outline"
                                            readonly
                                            clearable
                                        >
                                            <v-menu v-model="showMenu2" :close-on-content-click="false" activator="parent" min-width="0">
                                                <v-time-picker v-model="formData.hora_fin_recepcion_solicitudes" color="green"></v-time-picker>
                                            </v-menu>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="6">
                                        <v-locale-provider locale="es">
                                            <v-date-input
                                                required
                                                :rules="[rules.required]"
                                                icon-color="deep-orange"
                                                clearable
                                                v-model="formData.fecha_publicacion_resultados"
                                                :label="$t('convocatoria._fecha_publicacion_resultados_')"
                                                :hint="$t('convocatoria._fecha_publicacion_resultados_hint_')"
                                                persistent-hint
                                            ></v-date-input>
                                        </v-locale-provider>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field
                                            :model-value="formData.hora_publicacion_resultados"
                                            :label="$t('convocatoria._hora_publicacion_resultados_')"
                                            prepend-icon="mdi-clock-time-four-outline"
                                            readonly
                                            clearable
                                        >
                                            <v-menu v-model="showMenu" :close-on-content-click="false" activator="parent" min-width="0">
                                                <v-time-picker v-model="formData.hora_publicacion_resultados" color="green"></v-time-picker>
                                            </v-menu>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-number-input
                                            :max="100"
                                            :min="1"
                                            reverse
                                            control-variant="split"
                                            append-icon="mdi-percent"
                                            prepend-icon="mdi-counter"
                                            class="w-100"
                                            v-model="formData.cuota_sector_publico"
                                            :label="$t('convocatoria._cuota_sector_publico_')"
                                            :hint="$t('convocatoria._cuota_sector_publico_hint_')"
                                            persistent-hint
                                        ></v-number-input>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            required
                                            :rules="[rules.required]"
                                            icon-color="deep-orange"
                                            :label="$t('convocatoria._prueba_bachillerato_')"
                                            :items="props.pruebasBachillerato"
                                            v-model="formData.prueba_bachillerato_id"
                                            item-title="codigo"
                                            item-value="id"
                                            prepend-icon="mdi-form-dropdown"
                                            :hint="$t('convocatoria._prueba_bachillerato_hint_')"
                                            persistent-hint
                                        ></v-select>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" align="right">
                        <v-btn :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                            {{ $t('_guardar_') }}
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </template>
    </v-card>
</template>
