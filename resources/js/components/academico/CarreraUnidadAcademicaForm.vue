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
    semestre: number | null;
    obligatoria: boolean;
    requisito_creditos: number;
    area_id: number | null;
    unidad_academica_id: number | null;
    carrera_id: number | null;
    prerrequisitos: Array<[]>;
    correquisitos: Array<[]>;
}

const props = defineProps(['item', 'accion', 'areas', 'carreras', 'unidadesAcademicas', 'items', 'tiposRequisitos']);

const formData = ref<FormData>({
    id: null,
    semestre: null,
    obligatoria: true,
    requisito_creditos: 0,
    area_id: null,
    unidad_academica_id: null,
    carrera_id: null,
    prerrequisitos: [],
    correquisitos: [],
});
const isEditing = toRef(() => props.accion === 'edit');

const carreraUnidadesAcademicas = computed(() => {
    if (props.items) {
        return props.items.filter(
            (uc: any) => uc.carrera_id === formData.value.carrera_id && uc.unidad_academica_id != formData.value.unidad_academica_id,
        );
    } else {
        return [];
    }
});

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('academico-plan_estudio-malla_curricular-save'), formData.value);
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
            const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();

    filteredAssign(formData.value, props.item);
    formData.value.requisito_creditos = Number(props.item.requisito_creditos);
    if (isEditing.value) {
        const tipoPrerrequisito = props.tiposRequisitos.find((tipo) => tipo.codigo === 'PRERREQUISITO');
        const tipoCorrequisito = props.tiposRequisitos.find((tipo) => tipo.codigo === 'CORREQUISITO');

        formData.value.prerrequisitos = props.item.requisitos.filter((req) => req.pivot.tipo_requisito_id === tipoPrerrequisito.id);
        formData.value.correquisitos = props.item.requisitos.filter((req) => req.pivot.tipo_requisito_id === tipoCorrequisito.id);
    }
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
                            item-title="nombreCompleto"
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
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-locale-provider locale="en">
                            <v-number-input
                                prepend-icon="mdi-form-textbox"
                                v-model="formData.requisito_creditos"
                                :min="0"
                                :precision="null"
                                :label="$t('mallaCurricular._requisito_creditos_')"
                            ></v-number-input>
                        </v-locale-provider>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-checkbox v-model="formData.obligatoria" :label="$t('mallaCurricular._obligatoria_')"></v-checkbox>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-autocomplete
                            clearable
                            :label="$t('mallaCurricular._prerrequisitos_')"
                            :items="carreraUnidadesAcademicas"
                            v-model="formData.prerrequisitos"
                            item-title="unidad_academica.nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                            multiple
                            chips
                        ></v-autocomplete>
                    </v-col>
                    <!--<v-col cols="12" md="6">
                        <v-autocomplete
                            clearable
                            :label="$t('mallaCurricular._correquisitos_')"
                            :items="carreraUnidadesAcademicas"
                            v-model="formData.correquisitos"
                            item-title="unidad_academica.nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                            multiple
                            chips
                        ></v-autocomplete>
                    </v-col>-->

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
