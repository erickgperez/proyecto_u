<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();

const loading = ref(false);
const step = ref(1);
const formRef = ref<VForm | null>(null);
const convocatorias = ref([]);
const carreras = ref([]);
const sedes = ref([]);

interface FormData {
    convocatoria_id: number | null;
    sede_id: number | null;
    carrera_sede: [];
}

const props = defineProps(['persona', 'aspirante']);

const formData = ref<FormData>({
    convocatoria_id: null,
    sede_id: null,
    carrera_sede: [],
});

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        //formData.value.cuerpo_mensaje = content.value;
        try {
            const resp = await axios.postForm(route('ingreso-convocatoria-save'), formData.value, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            if (resp.data.status == 'ok') {
                Swal.fire({
                    title: t('_exito_'),
                    text: t('_datos_subidos_correctamente_'),
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    toast: true,
                });
            } else {
                hasError.value = true;
                message.value = t(resp.data.message);
            }
        } catch (error: any) {
            hasError.value = true;
            message.value = t('_no_se_pudo_guardar_formulario_');
            console.log(error);
        }

        if (hasError.value) {
            Swal.fire({
                title: t('_error_'),
                text: message.value,
                icon: 'error',
                confirmButtonColor: '#D7E1EE',
            });
        }
    }
    loading.value = false;
}

onMounted(() => {
    axios
        .get(route('ingreso-aspirante-convocatoria-carrera', { id: props.aspirante.id }))
        .then(function (response) {
            //console.log(response.data);

            convocatorias.value = response.data.convocatorias;
            carreras.value = response.data.carreras;
            //sedes.value = response.data.sedes;
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});

const oferta = ref([]);
const ofertaSede = ref([]);
const carrerasSeleccionadas = ref([]);
const form1 = ref(null);
const form2 = ref(null);
const form3 = ref(null);

const forms = [form1, form2, form3];

async function nextStep() {
    const currentFormRef = forms[step.value - 1];
    if (currentFormRef.value) {
        const { valid } = await currentFormRef.value.validate();
        if (valid) {
            step.value++;
        }
        if (step.value === 2 && formData.value.convocatoria_id != null) {
            const resp = await axios.get(route('ingreso-convocatoria-oferta', { id: formData.value.convocatoria_id }));
            oferta.value = resp.data.oferta;
            sedes.value = oferta.value.map(({ id, title }) => ({ id, title }));
        }

        if (step.value === 3 && formData.value.sede_id != null) {
            ofertaSede.value = oferta.value.filter((item) => item.id === formData.value.sede_id);
            carrerasSeleccionadas.value = [];
        }
    }
}

function prevStep() {
    step.value--;
}

watch(carrerasSeleccionadas, (newSelected) => {
    if (newSelected.length > 3) {
        carrerasSeleccionadas.value = newSelected.slice(0, 3);
        snackbar.value = true;
    }
});

const snackbar = ref(false);

const text = t('ingreso._solo_tres_opciones_');
</script>
<template>
    <v-stepper alt-labels hide-actions v-model="step">
        <v-stepper-header>
            <v-stepper-item :value="1" :title="$t('ingreso._seleccion_convocatoria_')" :bg-color="step === 1 ? 'primary' : ''"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :value="2" :title="$t('sede._sede_')"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :value="3" :title="$t('ingreso._seleccion_carreras_')"></v-stepper-item>
        </v-stepper-header>
        <v-stepper-window>
            <v-stepper-window-item :value="1">
                <v-card :title="$t('ingreso._seleccion_convocatoria_indicacion_')" flat>
                    <v-form fast-fail ref="form1">
                        <v-autocomplete
                            clearable
                            :label="$t('convocatoria._convocatoria_')"
                            :items="convocatorias"
                            v-model="formData.convocatoria_id"
                            item-title="nombre"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-form>
                </v-card>
                <v-btn @click="nextStep" rounded variant="tonal" color="primary" append-icon="mdi-chevron-right">{{ $t('_siguiente_') }}</v-btn>
            </v-stepper-window-item>

            <v-stepper-window-item :value="2">
                <v-card :title="$t('ingreso._seleccion_sede_indicacion_')" flat>
                    <v-form fast-fail ref="form2">
                        <v-autocomplete
                            clearable
                            :label="$t('sede._sede_')"
                            :items="sedes"
                            v-model="formData.sede_id"
                            item-title="title"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-form>
                    <v-btn class="me-4" rounded variant="tonal" color="secundary" prepend-icon="mdi-chevron-left" @click="prevStep">
                        {{ $t('_atras_') }}
                    </v-btn>
                    <v-btn @click="nextStep" rounded variant="tonal" color="primary" append-icon="mdi-chevron-right">
                        {{ $t('_siguiente_') }}
                    </v-btn>
                </v-card>
            </v-stepper-window-item>

            <v-stepper-window-item :value="3">
                <v-card flat>
                    <v-card-text class="pt-4">
                        {{ $t('ingreso._seleccion_carreras_indicacion_') }}
                        <v-row>
                            <v-col class="pa-6" cols="12" md="6">
                                <v-form fast-fail ref="form3">
                                    <v-treeview
                                        v-model:selected="carrerasSeleccionadas"
                                        open-all
                                        select-strategy="leaf"
                                        :items="ofertaSede"
                                        item-value="id"
                                        selectable
                                        return-object
                                        selected-color="green"
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
                                </v-form>
                            </v-col>
                            <v-col class="pa-6" cols="12" md="6">
                                <div v-if="carrerasSeleccionadas.length > 0">
                                    <v-icon color="success" size="x-large" icon="mdi-numeric-1-box"></v-icon> {{ $t('ingreso._primera_opcion_') }}:
                                    <span class="text-success text-high-emphasis font-weight-black">{{ carrerasSeleccionadas[0].title }}</span>
                                </div>
                                <div v-if="carrerasSeleccionadas.length > 1">
                                    <v-icon color="warning" size="x-large" icon="mdi-numeric-2-box"></v-icon> {{ $t('ingreso._segunda_opcion_') }}:
                                    <span class="text-warning text-high-emphasis font-weight-black">{{ carrerasSeleccionadas[1].title }}</span>
                                </div>
                                <div v-if="carrerasSeleccionadas.length > 2">
                                    <v-icon color="primary" size="x-large" icon="mdi-numeric-3-box"></v-icon> {{ $t('ingreso._tercera_opcion_') }}:
                                    <span class="text-high-emphasis font-weight-black text-primary"> {{ carrerasSeleccionadas[2].title }}</span>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-btn class="me-4" rounded variant="tonal" color="secundary" prepend-icon="mdi-chevron-left" @click="prevStep">
                        {{ $t('_atras_') }}
                    </v-btn>
                    <v-col cols="12" align="right">
                        <v-btn :loading="loading" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                            {{ $t('_guardar_') }}
                        </v-btn>
                    </v-col>
                </v-card>
            </v-stepper-window-item>
        </v-stepper-window>
    </v-stepper>
    <v-snackbar v-model="snackbar" multi-line>
        {{ text }}

        <template v-slot:actions>
            <v-btn color="white" @click="snackbar = false" icon="mdi-close-circle-outline"></v-btn>
        </template>
    </v-snackbar>
</template>
