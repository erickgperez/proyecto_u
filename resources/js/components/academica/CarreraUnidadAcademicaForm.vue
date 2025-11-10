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
    semestre: number | null;
    obligatoria: boolean;
    requisito_creditos: number;
    area_id: number | null;
    unidad_academica_id: number | null;
    carrera_id: number | null;
}

const props = defineProps(['item', 'accion', 'areas', 'carreras', 'unidadesAcademicas']);

const formData = ref<FormData>({
    id: null,
    semestre: null,
    obligatoria: true,
    requisito_creditos: 0,
    area_id: null,
    unidad_academica_id: null,
    carrera_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('plan_estudio-malla_curricular-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);
                //localItem.value.afiche = resp.data.convocatoria.afiche;
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            const msj = error instanceof Error ? t(error.message) : error.response.data.message;
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();

    formData.value = { ...props.item };
    formData.value.requisito_creditos = Number(props.item.requisito_creditos);
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('mallaCurricular._editar_') : $t('mallaCurricular._crear_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-autocomplete
                            clearable
                            :label="$t('carrera._singular_') + ' *'"
                            :items="props.carreras"
                            v-model="formData.carrera_id"
                            :rules="[rules.required]"
                            icon-color="deep-orange"
                            item-title="nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                        <v-number-input
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.semestre"
                            :rules="[rules.required]"
                            :min="1"
                            :label="$t('mallaCurricular._semestre_') + ' *'"
                        ></v-number-input>

                        <v-autocomplete
                            clearable
                            :label="$t('unidadAcademica._singular_') + ' *'"
                            :items="props.unidadesAcademicas"
                            v-model="formData.unidad_academica_id"
                            :rules="[rules.required]"
                            icon-color="deep-orange"
                            item-title="nombre"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                        <v-autocomplete
                            clearable
                            :label="$t('area._singular_') + ' *'"
                            :items="props.areas"
                            v-model="formData.area_id"
                            item-title="descripcion"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                        <v-checkbox v-model="formData.obligatoria" :label="$t('mallaCurricular._obligatoria_')"></v-checkbox>
                        <v-locale-provider locale="en">
                            <v-number-input
                                icon-color="deep-orange"
                                prepend-icon="mdi-form-textbox"
                                v-model="formData.requisito_creditos"
                                :min="0"
                                :precision="null"
                                :label="$t('mallaCurricular._requisito_creditos_')"
                            ></v-number-input>
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
