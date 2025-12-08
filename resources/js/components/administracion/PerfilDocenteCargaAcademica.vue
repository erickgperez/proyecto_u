<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
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
        //formData.value.cargaTitular = formData.value.cargaTitular.map((item) => item.id);
        //formData.value.cargaAsociado = formData.value.cargaAsociado.map((item) => item.id);
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

const cargaTitular = computed(() => {
    return ofertaCarrera.value.flatMap((item) => item.children.filter((child) => formData.value.cargaTitular.includes(child.id)));
});
const cargaAsociado = computed(() => {
    return ofertaCarreraSede.value.flatMap((item) => formData.value.cargaAsociado.includes(item.id));
});

const sede = ref(null);
const carreraSede = ref(null);
const unidadImpartida = ref([]);

const sedes = computed(() => {
    const seenIds = new Set();
    return ofertaCarreraSede.value
        .map((item) => ({
            id: item.carrera_sede.sede.id,
            nombre: `(${item.carrera_sede.sede.codigo}) - ${item.carrera_sede.sede.nombre}`,
        }))
        .filter((sede) => {
            const isDuplicate = seenIds.has(sede.id);
            seenIds.add(sede.id);
            return !isDuplicate;
        })
        .sort((a, b) => a.nombre.localeCompare(b.nombre));
});

const carrerasSede = computed(() => {
    const seenIds = new Set();
    return ofertaCarreraSede.value
        .map((item) => ({
            id: item.carrera_sede.id,
            sede_id: item.carrera_sede.sede_id,
            nombre: item.carrera_sede.carrera.nombreCompleto,
        }))
        .filter((carrera) => {
            const isDuplicate = seenIds.has(carrera.id);
            seenIds.add(carrera.id);
            return !isDuplicate;
        })
        .sort((a, b) => a.nombre.localeCompare(b.nombre));
});

const unidadesImpartidas = computed(() => {
    const seenIds = new Set();
    return ofertaCarreraSede.value
        .map((item) => ({
            id: item.id,
            carrera_sede_id: item.carrera_sede_id,
            nombre:
                '(Sede:' +
                item.carrera_sede.sede.codigo +
                ') (' +
                item.carrera_sede.carrera.codigo +
                ' ' +
                item.carrera_sede.carrera.nombre +
                ') (' +
                item.oferta.carrera_unidad_academica.semestre +
                ') ' +
                item.oferta.carrera_unidad_academica.unidad_academica.nombre,
        }))
        .filter((unidad) => {
            const isDuplicate = seenIds.has(unidad.id);
            seenIds.add(unidad.id);
            return !isDuplicate;
        })
        .sort((a, b) => a.nombre.localeCompare(b.nombre));
});

const carrerasSedeFiltradas = computed(() => {
    if (!sede.value) return [];
    return carrerasSede.value.filter((item) => item.sede_id === sede.value.id);
});

const unidadesImpartidasFiltradas = computed(() => {
    if (!carreraSede.value) return [];
    return unidadesImpartidas.value.filter((item) => item.carrera_sede_id === carreraSede.value.id);
});

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
                formData.value.cargaAsociado = response.data.cargaAsociado;
                formData.value.cargaTitular = response.data.cargaTitular.map((item) => item.id);
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
            {{ sede }}
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12" class="pt-5 pl-5">
                        <v-card class="mx-auto">
                            <v-toolbar class="bg-blue-grey-lighten-3">
                                <v-toolbar-title class="text-h6" :text="$t('perfil._docente_carga_academica_')"></v-toolbar-title>
                            </v-toolbar>

                            <v-card-text>
                                <v-row>
                                    <v-col cols="12">
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
                                        <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
                                            <v-tab value="1">{{ $t('perfil._docente_carga_academica_titular_') }}</v-tab>
                                            <v-tab value="2">{{ $t('perfil._docente_carga_academica_asociado_') }}</v-tab>
                                        </v-tabs>

                                        <v-tabs-window v-model="tab">
                                            <v-tabs-window-item value="1">
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <v-divider class="mb-4"></v-divider>
                                                        <span>{{ $t('perfil._elija_carga_academica_') }}</span>
                                                        <v-treeview
                                                            v-model:selected="formData.cargaTitular"
                                                            :items="ofertaCarrera"
                                                            item-value="id"
                                                            select-strategy="leaf"
                                                            selectable
                                                            :indent-lines="true"
                                                        >
                                                            <template v-slot:toggle="{ props: toggleProps, isOpen, isSelected, isIndeterminate }">
                                                                <v-badge
                                                                    :color="isSelected ? 'success' : 'warning'"
                                                                    :model-value="isSelected || isIndeterminate"
                                                                >
                                                                    <template v-slot:badge>
                                                                        <v-icon v-if="isSelected" icon="$complete"></v-icon>
                                                                    </template>
                                                                    <v-btn
                                                                        v-bind="toggleProps"
                                                                        :color="
                                                                            isIndeterminate ? 'warning' : isSelected ? 'success' : 'medium-emphasis'
                                                                        "
                                                                        :variant="isOpen ? 'outlined' : 'tonal'"
                                                                    ></v-btn>
                                                                </v-badge>
                                                            </template>
                                                        </v-treeview>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-list lines="one" v-if="cargaTitular.length > 0">
                                                            <v-list-subheader inset>{{
                                                                $t('perfil._docente_carga_academica_asignada_')
                                                            }}</v-list-subheader>

                                                            <v-list-item
                                                                v-for="item in cargaTitular"
                                                                :key="item.id"
                                                                :title="item.carrera"
                                                                :subtitle="item.unidad"
                                                            ></v-list-item>
                                                        </v-list>
                                                    </v-col>
                                                </v-row>
                                            </v-tabs-window-item>

                                            <v-tabs-window-item value="2">
                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-divider class="mb-4"></v-divider>
                                                        <span>{{ $t('perfil._elija_carga_academica_') }}</span>
                                                        <v-select
                                                            v-model="sede"
                                                            :items="sedes"
                                                            item-value="id"
                                                            item-title="nombre"
                                                            select-strategy="leaf"
                                                            selectable
                                                            :indent-lines="true"
                                                            return-object
                                                        ></v-select>
                                                        <v-select
                                                            v-model="carreraSede"
                                                            :items="carrerasSedeFiltradas"
                                                            item-value="id"
                                                            item-title="nombre"
                                                            item-subtitle="sede_id"
                                                            select-strategy="leaf"
                                                            selectable
                                                            :indent-lines="true"
                                                            return-object
                                                        ></v-select>

                                                        <v-select
                                                            v-model="formData.cargaAsociado"
                                                            :items="unidadesImpartidasFiltradas"
                                                            item-value="id"
                                                            item-title="nombre"
                                                            item-subtitle="sede_id"
                                                            select-strategy="leaf"
                                                            selectable
                                                            :indent-lines="true"
                                                            chips
                                                            return-object
                                                            multiple
                                                        ></v-select>
                                                    </v-col>
                                                </v-row>
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
