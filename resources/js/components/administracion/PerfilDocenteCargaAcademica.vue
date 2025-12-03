<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { mensajeError, mensajeExito } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);
const tab = ref(1);

//const emit = defineEmits(['form-saved']);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
}

interface FormData {
    id: number | null;
    cargaTitular: [];
    cargaAsociado: [];
}

const props = defineProps(['item', 'accion']);

const formData = ref<FormData>({
    id: null,
    cargaTitular: [],
    cargaAsociado: [],
});

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.postForm(route('academico-semestre-docente-carga-save', { uuid: props.item.docente.uuid }), formData.value);
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

const semestres = ref([]);
const semestre = ref(null);
const ofertaCarreraSede = ref([]);
const ofertaCarrera = ref([]);

onMounted(() => {
    reset();
    axios
        .get(route('academico-semestre-activos'))
        .then(function (response) {
            semestres.value = response.data.semestres;
        })
        .catch(function (error) {
            console.error('Error fetching data:', error);
        });
});

watch(semestre, (newVal) => {
    if (newVal) {
        axios
            .get(route('academico-semestre-docente-carga', { uuidSemestre: newVal, uuidDocente: props.item.docente.uuid }))
            .then(function (response) {
                ofertaCarreraSede.value = response.data.ofertaCarreraSede;
                ofertaCarrera.value = response.data.ofertaCarrera;
                formData.value.cargaAsociado = response.data.cargaAsociado.map((item) => item.id);
            })
            .catch(function (error) {
                console.error('Error fetching data:', error);
            });
    }
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
                                <v-toolbar-title class="text-h6" :text="$t('perfil._asignado_en_')"></v-toolbar-title>
                            </v-toolbar>

                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" >
                                        <v-select
                                            :label="$t('semestre._singular_')"
                                            :items="semestres"
                                            v-model="semestre"
                                            item-title="nombre"
                                            item-value="uuid"
                                            prepend-icon="mdi-form-dropdown"
                                            chips
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" v-if="semestre">
                                        <v-tabs
                                            v-model="tab"
                                            align-tabs="center"
                                            color="deep-purple-accent-4"
                                            >
                                            <v-tab value="1">Como docente titular</v-tab>
                                            <v-tab value="2">Como docente asociado</v-tab>
                                            </v-tabs>

                                            <v-tabs-window v-model="tab">
                                                <v-tabs-window-item value="1">
                                                    <v-treeview
                                                        v-model:selected="formData.cargaTitular"
                                                        :items="ofertaCarrera"
                                                        item-value="id"
                                                        select-strategy="leaf"
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
                                                </v-tabs-window-item>

                                                <v-tabs-window-item value="2">
                                                    <v-treeview
                                                        v-model:selected="formData.cargaAsociado"
                                                        :items="ofertaCarreraSede"
                                                        item-value="id"
                                                        select-strategy="leaf"
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
                                            </v-tabs-window-item>
                                        </v-tabs-window>
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
