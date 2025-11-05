<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { mensajeError, mensajeExito } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    formRef.value!.reset();
}

interface FormData {
    id: number | null;
    carreras_sedes: [];
}

const props = defineProps(['item', 'accion', 'sedesCarreras']);

const formData = ref<FormData>({
    id: null,
    carreras_sedes: [],
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.postForm(route('ingreso-convocatoria-oferta-save', { id: props.item.id }), formData.value, {
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

    formData.value.carreras_sedes = props.item.carreras_sedes.map((cs: any) => cs.id);
});
</script>
<template>
    <v-alert border="top" type="warning" variant="outlined" prominent v-if="item.solicitud && item.solicitud.etapa.codigo != 'OFERTA'">
        {{ $t('convocatoria._alerta_oferta_') }}
    </v-alert>
    <v-card :title="item.descripcion">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12" class="pt-15 pl-15">
                        <v-card class="mx-auto">
                            <v-toolbar class="bg-blue-grey-lighten-3">
                                <v-toolbar-title class="text-h6" :text="$t('convocatoria._oferta_')"></v-toolbar-title>
                            </v-toolbar>

                            <v-card-text>
                                <div class="font-weight-bold ms-1 mb-2">{{ $t('convocatoria._oferta_indicaciones_') }}</div>

                                <v-treeview
                                    v-model:selected="formData.carreras_sedes"
                                    :items="props.sedesCarreras"
                                    item-value="id"
                                    select-strategy="classic"
                                    selectable
                                    :indent-lines="true"
                                >
                                    <template v-slot:toggle="{ props: toggleProps, isOpen, isSelected, isIndeterminate }">
                                        <v-badge :color="isSelected ? 'success' : 'warning'" :model-value="isSelected || isIndeterminate">
                                            <template v-slot:badge>
                                                <v-icon v-if="isSelected" icon="$complete"></v-icon>
                                            </template>
                                            <v-btn
                                                v-bind="toggleProps"
                                                :color="isIndeterminate ? 'warning' : isSelected ? 'success' : 'medium-emphasis'"
                                                :variant="isOpen ? 'outlined' : 'tonal'"
                                            ></v-btn>
                                        </v-badge>
                                    </template>
                                </v-treeview>
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
