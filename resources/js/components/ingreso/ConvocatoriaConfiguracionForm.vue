<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    formRef.value!.reset();
}

interface FormData {
    id: number | null;
    fecha_publicacion_resultados: Date | null;
    cuota_sector_publico: number | null;
}

const props = defineProps(['item', 'accion']);

const formData = ref<FormData>({
    id: null,
    fecha_publicacion_resultados: null,
    cuota_sector_publico: null,
});
const isEditing = toRef(() => props.accion === 'configuracion');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

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
                Swal.fire({
                    title: t('_exito_'),
                    text: t('_datos_subidos_correctamente_'),
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    toast: true,
                });
            } else {
                hasError.value = true;
                message.value = t(resp.data.message);
            }
        } catch (error: any) {
            hasError.value = true;
            message.value = t('_no_se_pudo_guardar_formulario_');
            console.log(error);
        }

        if (hasError.value) {
            Swal.fire({
                title: t('_error_'),
                text: message.value,
                icon: 'error',
                confirmButtonColor: '#D7E1EE',
            });
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();
    if (props.item.configuracion != null) {
        formData.value = { ...props.item.configuracion };
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
                                <v-locale-provider locale="es">
                                    <v-date-input
                                        clearable
                                        v-model="formData.fecha_publicacion_resultados"
                                        :label="$t('convocatoria._fecha_publicacion_resultados_')"
                                        :hint="$t('convocatoria._fecha_publicacion_resultados_hint_')"
                                        persistent-hint
                                    ></v-date-input>
                                </v-locale-provider>
                                <v-divider></v-divider>
                                <v-number-input
                                    :max="100"
                                    :min="1"
                                    reverse
                                    control-variant="split"
                                    append-icon="mdi-percent"
                                    prepend-icon="mdi-counter"
                                    class="w-50"
                                    v-model="formData.cuota_sector_publico"
                                    :label="$t('convocatoria._cuota_sector_publico_')"
                                    :hint="$t('convocatoria._cuota_sector_publico_hint_')"
                                    persistent-hint
                                ></v-number-input>
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
