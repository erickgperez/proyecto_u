<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { mensajeError, mensajeExito } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

//const emit = defineEmits(['form-saved']);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
}

interface FormData {
    id: number | null;
    carreras_sedes: [];
    carrera_sede_principal_id: number | null;
}

const props = defineProps(['item', 'accion']);

const formData = ref<FormData>({
    id: null,
    carreras_sedes: [],
    carrera_sede_principal_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.postForm(route('administracion-persona-docente-asignacion-save', { id: props.item.id }), formData.value);
            if (resp.data.status == 'ok') {
                //emit('form-saved', resp.data.convocatoria);
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

interface Docente {
    id: number | null;
    carreras_sedes: Array<[]>;
}

const carrerasSedes = ref([]);
const carrerasSedesPlain = ref([]);
const carrerasSedesSeleccionadas = computed(() => {
    return carrerasSedesPlain.value.filter((cs: any) => formData.value.carreras_sedes.includes(cs.id));
});

const docente = ref<Docente>({
    id: null,
    carreras_sedes: [],
});

onMounted(() => {
    reset();
    axios
        .get(route('academico-plan_estudio-carrera-sede'))
        .then(function (response) {
            carrerasSedes.value = response.data.carrerasSedes;
            carrerasSedesPlain.value = response.data.carrerasSedesPlain;
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
    axios
        .get(route('administracion-persona-docente-data', { id: props.item.id }))
        .then(function (response) {
            docente.value = response.data.docente;
            formData.value.carreras_sedes = docente.value.carreras_sedes.map((cs: any) => cs.id);

            const principal = docente.value.carreras_sedes.filter((cs) => cs.pivot.principal === true);
            if (principal.length > 0) {
                formData.value.carrera_sede_principal_id = principal[0].id;
            }
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });

    //
});
</script>
<template>
    <v-card :title="item.nombreCompleto">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12" class="pt-5 pl-5">
                        <v-card class="mx-auto">
                            <v-toolbar class="bg-blue-grey-lighten-3">
                                <v-toolbar-title class="text-h6" :text="$t('persona._asignado_en_')"></v-toolbar-title>
                            </v-toolbar>

                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            :label="$t('persona._asignacion_principal_')"
                                            :items="carrerasSedesSeleccionadas"
                                            v-model="formData.carrera_sede_principal_id"
                                            item-title="tituloAbr"
                                            item-value="id"
                                            prepend-icon="mdi-form-dropdown"
                                            chips
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <div class="font-weight-bold ms-1 mb-2">{{ $t('persona._asignado_en_indicaciones_') }}</div>
                                        <v-treeview
                                            v-model:selected="formData.carreras_sedes"
                                            :items="carrerasSedes"
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
                                    </v-col>
                                </v-row>
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
