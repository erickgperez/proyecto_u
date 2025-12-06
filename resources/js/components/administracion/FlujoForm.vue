<script setup lang="ts">
import { useFilteredMerge } from '@/composables/useFilteredMerge';
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

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
    nombre: string;
    activo: boolean;
    tipo_flujo_id: number | null;
}

const props = defineProps(['item', 'accion', 'tipos']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    activo: false,
    tipo_flujo_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('procesos-proceso-save'), formData.value);
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
    return isEditing.value ? t('flujo._editar_') : t('flujo._crear_');
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
                            v-model="formData.codigo"
                            :rules="[rules.required, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('flujo._codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[rules.required, rules.maxLength(255)]"
                            counter="255"
                            :label="$t('flujo._nombre_') + ' *'"
                        ></v-text-field>

                        <v-checkbox
                            icon-color="deep-orange"
                            class="ml-8"
                            v-model="formData.activo"
                            color="primary"
                            :label="$t('flujo._activo_') + ' *'"
                        ></v-checkbox>

                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            :rules="[rules.required]"
                            :label="$t('flujo._tipo_') + ' *'"
                            :items="tipos"
                            v-model="formData.tipo_flujo_id"
                            item-title="codigo"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
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
