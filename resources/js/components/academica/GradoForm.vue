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

const emit = defineEmits(['form-saved']);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
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

    if (valid) {
        try {
            const resp = await axios.postForm(route('plan_estudio-grado-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);

                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            const msj = axios.isAxiosError(error) ? error.response.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();
    formData.value = { ...props.item };
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('grado._editar_grado_') : $t('grado._crear_grado_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            prepend-icon="mdi-form-textbox"
                            icon-color="deep-orange"
                            v-model="formData.codigo"
                            :rules="[rules.required, rules.maxLength(15)]"
                            counter="15"
                            :label="$t('_codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.descripcion_masculino"
                            :rules="[rules.required, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('grado._descripcion_masculino_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.descripcion_femenino"
                            :rules="[rules.required, rules.maxLength(100)]"
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
