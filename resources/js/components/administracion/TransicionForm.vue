<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, toRef } from 'vue';
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
    nombre: string;
    flujo_id: number | null;
    etapa_origen_id: number | null;
    etapa_destino_id: number | null;
    estado_origen_id: number | null;
    estado_destino_id: number | null;
}

const props = defineProps(['item', 'accion', 'flujos', 'etapas', 'estados']);

const etapasByFlujo = computed(() => {
    return props.etapas.filter((etapa: any) => etapa.flujo_id == formData.value.flujo_id);
});

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    flujo_id: null,
    etapa_origen_id: null,
    etapa_destino_id: null,
    estado_origen_id: null,
    estado_destino_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('proceso-transicion-save'), formData.value);
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

    formData.value = { ...props.item };
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('transicion._editar_') : $t('transicion._crear_')} `">
        <template v-slot:text>
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
                            :label="$t('transicion._codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[rules.required, rules.maxLength(255)]"
                            counter="255"
                            :label="$t('transicion._nombre_') + ' *'"
                        ></v-text-field>

                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            :rules="[rules.required]"
                            :label="$t('flujo._singular_') + ' *'"
                            :items="flujos"
                            v-model="formData.flujo_id"
                            item-title="codigo"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-col>
                    <v-col cols="6">
                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            :rules="[rules.required]"
                            :label="$t('transicion._etapa_origen_') + ' *'"
                            :items="etapasByFlujo"
                            v-model="formData.etapa_origen_id"
                            item-title="codigo"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-col>
                    <v-col cols="6">
                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            :rules="[rules.required]"
                            :label="$t('transicion._estado_origen_') + ' *'"
                            :items="estados"
                            v-model="formData.estado_origen_id"
                            item-title="codigo"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-col>
                    <v-col cols="6">
                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            :rules="[rules.required]"
                            :label="$t('transicion._etapa_destino_') + ' *'"
                            :items="etapasByFlujo"
                            v-model="formData.etapa_destino_id"
                            item-title="codigo"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-col>
                    <v-col cols="6">
                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            :rules="[rules.required]"
                            :label="$t('transicion._estado_destino_') + ' *'"
                            :items="estados"
                            v-model="formData.estado_destino_id"
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
        </template>
    </v-card>
</template>
