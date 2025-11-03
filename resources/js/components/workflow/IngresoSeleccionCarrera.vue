<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import CarrerasSeleccionadas from '../ingreso/CarrerasSeleccionadas.vue';

const { t } = useI18n();
const { mensajeError, mensajeExito } = useFunciones();

const emit = defineEmits(['form-saved']);

const loading = ref(false);
const step = ref(1);
const sedes = ref([]);
const oferta = ref([]);
const ofertaSede = ref([]);
const snackbar = ref(false);
const text = ref('');
const form1 = ref(null);
const form2 = ref(null);
const sede = ref(null);
const carrerasSeleccionadas = ref([]);

interface FormData {
    sede_id: number | null;
    carrera_sede: [];
}

const props = defineProps(['solicitud']);

const formData = ref<FormData>({
    sede_id: null,
    carrera_sede: [],
});

async function submitForm() {
    loading.value = true;

    formData.value.sede_id = sede.value.id.split('-').pop();
    formData.value.carrera_sede = carrerasSeleccionadas.value.map((carr) => carr.id);
    try {
        const resp = await axios.post(route('workflow-ingreso-solicitud-seleccion-carrera', { id: props.solicitud.id }), formData.value);
        if (resp.data.status == 'ok') {
            emit('form-saved', resp.data);
            mensajeExito(t('_datos_subidos_correctamente_'));
        } else {
            throw new Error(resp.data.message);
        }
    } catch (error: any) {
        console.log(error);
        mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + error.message);
    }

    loading.value = false;
}

async function validarCarreras() {
    if (carrerasSeleccionadas.value.length > 0) {
        const continuar = ref(true);

        if (carrerasSeleccionadas.value.length < 3) {
            continuar.value = false;
            Swal.fire({
                title: t('ingreso._carreras_incompletas_'),
                text: t('ingreso._no_eleccion_carreras_alternativas_'),
                showCancelButton: true,
                confirmButtonText: t('_continuar_'),
                cancelButtonText: t('_cancelar_'),
                confirmButtonColor: '#e5adac',
                cancelButtonColor: '#D7E1EE',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    step.value++;
                }
            });
        } else {
            step.value++;
        }
    } else {
        text.value = t('ingreso._debe_seleccionar_al_menos_una_carrera_');
        snackbar.value = true;
    }
}

onMounted(() => {
    axios
        .get(route('ingreso-convocatoria-oferta', { id: props.solicitud.modelo.id }))
        .then(function (response) {
            oferta.value = response.data.oferta;
            sedes.value = oferta.value.map(({ id, title }) => ({ id, title }));
        })
        .catch(function (error) {
            console.error('Error fetching data:', error);
        });
});

const forms = [form1, form2];

async function nextStep() {
    const currentFormRef = forms[step.value - 1];
    if (currentFormRef.value) {
        const { valid } = await currentFormRef.value.validate();
        if (valid) {
            step.value++;
        }
        /*if (step.value === 2 && convocatoria.value != null) {
            const resp = await axios.get(route('ingreso-convocatoria-oferta', { id: convocatoria.value.id }));
            oferta.value = resp.data.oferta;
            sedes.value = oferta.value.map(({ id, title }) => ({ id, title }));
        }*/

        /*if (step.value === 3 && sede.value != null) {
        }*/
    }
}

function prevStep() {
    step.value--;
}

watch(carrerasSeleccionadas, (newSelected) => {
    if (newSelected.length > 3) {
        text.value = t('ingreso._solo_tres_opciones_');
        carrerasSeleccionadas.value = newSelected.slice(0, 3);
        snackbar.value = true;
    }
});

watch(sede, (newSede) => {
    if (newSede != null) {
        ofertaSede.value = oferta.value.filter((item) => item.id === sede.value.id);
    } else {
        carrerasSeleccionadas.value = [];
    }
});
</script>
<template>
    <v-stepper alt-labels hide-actions v-model="step">
        <v-stepper-header>
            <v-stepper-item :color="step === 1 ? 'pink' : ''" :value="1">
                <span :class="step === 1 ? 'text-pink' : ''">{{ $t('sede._sede_') }}</span>
            </v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :color="step === 2 ? 'pink' : ''" :value="2">
                <span :class="step === 2 ? 'text-pink' : ''">{{ $t('ingreso._seleccion_carreras_') }}</span>
            </v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :color="step === 3 ? 'pink' : ''" :value="3">
                <span :class="step === 3 ? 'text-pink' : ''">{{ $t('ingreso._resumen_') }}</span>
            </v-stepper-item>
        </v-stepper-header>
        <v-stepper-window>
            <v-stepper-window-item :value="1">
                <v-card :title="$t('ingreso._seleccion_sede_indicacion_')" flat>
                    <v-card-text class="pt-4">
                        <v-form fast-fail ref="form1">
                            <v-autocomplete
                                clearable
                                :label="$t('sede._sede_')"
                                :items="sedes"
                                v-model="sede"
                                return-object
                                :rules="[(v) => !!v || $t('_campo_requerido_')]"
                                item-title="title"
                                item-value="id"
                                prepend-icon="mdi-form-dropdown"
                            ></v-autocomplete>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn class="me-4" rounded variant="tonal" color="secundary" prepend-icon="mdi-chevron-left" @click="prevStep">
                            {{ $t('_atras_') }}
                        </v-btn>
                        <v-btn @click="nextStep" rounded variant="tonal" color="primary" append-icon="mdi-chevron-right">
                            {{ $t('_siguiente_') }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-stepper-window-item>

            <v-stepper-window-item :value="2">
                <v-card flat>
                    <v-card-text class="pt-4">
                        {{ $t('ingreso._seleccion_carreras_indicacion_') }}
                        <v-row>
                            <v-col class="pa-6" cols="12" md="6">
                                <v-form fast-fail ref="form2">
                                    <v-treeview
                                        v-model:selected="carrerasSeleccionadas"
                                        open-all
                                        select-strategy="leaf"
                                        :items="ofertaSede"
                                        item-value="id"
                                        selectable
                                        return-object
                                        selected-color="pink-darken-3"
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
                                <CarrerasSeleccionadas :carrerasSeleccionadas="carrerasSeleccionadas"></CarrerasSeleccionadas>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn class="me-4" rounded variant="tonal" color="secundary" prepend-icon="mdi-chevron-left" @click="prevStep">
                            {{ $t('_atras_') }}
                        </v-btn>
                        <v-btn @click="validarCarreras" rounded variant="tonal" color="primary" append-icon="mdi-chevron-right">
                            {{ $t('_siguiente_') }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-stepper-window-item>
            <v-stepper-window-item :value="3">
                <v-card :title="$t('ingreso._resumen_')" flat>
                    <v-card-text class="pt-4">
                        {{ $t('ingreso._revise_informacion_') }}
                        <v-alert border="start" type="info" variant="outlined">
                            <template v-slot:title> {{ $t('ingreso._seleccion_convocatoria_') }} </template>

                            <span class="text-h6 text-black">{{ props.solicitud.modelo.nombre }} -- {{ props.solicitud.modelo.descripcion }}</span>
                        </v-alert>

                        <br />

                        <v-alert border="start" type="info" variant="outlined">
                            <template v-slot:title> {{ $t('sede._sede_') }} </template>
                            <span class="text-h6 text-black">{{ sede.title }}</span>
                        </v-alert>

                        <br />

                        <v-alert border="start" type="info" variant="outlined">
                            <template v-slot:title> {{ $t('ingreso._seleccion_carreras_') }} </template>
                            <CarrerasSeleccionadas :carrerasSeleccionadas="carrerasSeleccionadas"></CarrerasSeleccionadas>
                        </v-alert>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn class="me-4" rounded variant="tonal" color="secundary" prepend-icon="mdi-chevron-left" @click="prevStep">
                            {{ $t('_atras_') }}
                        </v-btn>

                        <v-spacer></v-spacer>
                        <v-btn :loading="loading" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save" @click="submitForm">
                            {{ $t('_guardar_') }}
                        </v-btn>
                    </v-card-actions>
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
