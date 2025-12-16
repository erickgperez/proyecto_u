<script setup lang="ts">
import { useFilteredMerge } from '@/composables/useFilteredMerge';
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { filteredAssign } = useFilteredMerge();
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
    nombre: string;
    institucion: string;
    fecha_finalizacion: Date | null;
}

const props = defineProps(['item', 'accion', 'persona']);

const formData = ref<FormData>({
    id: null,
    nombre: '',
    institucion: '',
    fecha_finalizacion: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('administracion-estudio-save', { uuid: props.persona.uuid }), formData.value);
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

const titleForm = computed(() => {
    return isEditing.value ? t('estudio._editar_') : t('estudio._crear_');
});
</script>
<template>
    <v-card class="rounded-t-xl">
        <v-card-title class="border-b-md bg-blue-grey-lighten-3">
            {{ titleForm }}
        </v-card-title>
        <v-card-text class="pt-4">
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[rules.required, rules.maxLength(255)]"
                            counter="255"
                            :label="$t('estudio._nombre_') + ' *'"
                            :hint="$t('estudio._nombre_hint_')"
                            persistent-hint
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.institucion"
                            :rules="[rules.required, rules.maxLength(255)]"
                            counter="255"
                            :label="$t('estudio._institucion_')"
                            :hint="$t('estudio._institucion_hint_')"
                            persistent-hint
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-locale-provider locale="es">
                            <v-date-input
                                clearable
                                icon-color="deep-orange"
                                required
                                v-model="formData.fecha_finalizacion"
                                :rules="[rules.required]"
                                :label="$t('estudio._fecha_finalizacion_') + ' *'"
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
        </v-card-text>
    </v-card>
</template>
