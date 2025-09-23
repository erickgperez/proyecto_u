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
    codigo: string;
    descripcion_masculino: string;
    descripcion_femenino: string;
}

const props = defineProps(['item', 'accion']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    descripcion_masculino: '',
    descripcion_femenino: '',
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.postForm(route('plan_estudio-grado-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);

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
    if (props.accion === 'edit') {
        formData.value = { ...props.item };
    }
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('grado._editar_grado_') : $t('grado._crear_grado_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => !v || v.length <= 15 || $t('_longitud_maxima_') + ': 15 ' + $t('_caracteres_'),
                            ]"
                            counter="15"
                            :label="$t('_codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.descripcion_masculino"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => (!!v && v.length <= 100) || $t('_longitud_maxima') + ': 100 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('grado._descripcion_masculino_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.descripcion_femenino"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => (!!v && v.length <= 100) || $t('_longitud_maxima') + ': 100 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('grado._descripcion_femenino_') + ' *'"
                        ></v-text-field>
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
