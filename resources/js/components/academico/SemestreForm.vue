<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';
import { useFilteredMerge } from '@/composables/useFilteredMerge';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();
const { filteredAssign } = useFilteredMerge();

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
    descripcion: string;
    fecha_inicio: Date | null;
    fecha_fin: Date | null;
}

const props = defineProps(['item', 'accion']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    descripcion: '',
    fecha_inicio: null,
    fecha_fin: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('academico-semestre-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);
                //localItem.value.afiche = resp.data.convocatoria.afiche;
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                console.log(resp.data.message);
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();
    if (props.item) {
        filteredAssign(formData.value, props.item);
    }
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('semestre._editar_') : $t('semestre._crear_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[rules.required, rules.maxLength(50)]"
                            counter="50"
                            :label="$t('_codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field prepend-icon="mdi-form-textbox" v-model="formData.descripcion" :label="$t('_descripcion_')"></v-text-field>

                        <v-locale-provider locale="es">
                            <v-date-input
                                clearable
                                required
                                icon-color="deep-orange"
                                v-model="formData.fecha_inicio"
                                :rules="[rules.required]"
                                :label="$t('_fecha_inicio_') + ' *'"
                            ></v-date-input>
                        </v-locale-provider>
                        <v-locale-provider locale="es">
                            <v-date-input
                                clearable
                                required
                                icon-color="deep-orange"
                                v-model="formData.fecha_fin"
                                :rules="[rules.required]"
                                :label="$t('_fecha_fin_') + ' *'"
                            ></v-date-input>
                        </v-locale-provider>
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
