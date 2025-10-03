<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import CarrerasSeleccionadas from './CarrerasSeleccionadas.vue';

const { t } = useI18n();

const loading = ref(false);
const step = ref(1);
const convocatorias = ref([]);
const carreras = ref([]);
const sedes = ref([]);
const oferta = ref([]);
const ofertaSede = ref([]);
const snackbar = ref(false);
const text = ref('');
const form1 = ref(null);
const form2 = ref(null);
const form3 = ref(null);
const convocatoria = ref(null);
const sede = ref(null);
const carrerasSeleccionadas = ref([]);

interface FormData {
    convocatoria_id: number | null;
    carrera_sede: [];
}

const props = defineProps(['persona', 'aspirante']);

const formData = ref<FormData>({
    convocatoria_id: null,
    carrera_sede: [],
});

async function submitForm() {
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    formData.value.convocatoria_id = convocatoria.value.id;
    formData.value.carrera_sede = carrerasSeleccionadas.value.map((carr) => carr.id);
    try {
        const resp = await axios.post(route('ingreso-aspirante-seleccion-carrera', { id: props.aspirante.id }), formData.value);
        if (resp.data.status == 'ok') {
            Swal.fire({
                title: t('_exito_'),
                text: t('_datos_guardados_correctamente_'),
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
        .get(route('ingreso-aspirante-convocatoria-carrera', { id: props.aspirante.id }))
        .then(function (response) {
            convocatorias.value = response.data.convocatorias;
            carreras.value = response.data.carreras;
        })
        .catch(function (error) {
            console.error('Error fetching data:', error);
        });
});

const forms = [form1, form2, form3];

async function nextStep() {
    const currentFormRef = forms[step.value - 1];
    if (currentFormRef.value) {
        const { valid } = await currentFormRef.value.validate();
        if (valid) {
            step.value++;
        }
        if (step.value === 2 && convocatoria.value != null) {
            const resp = await axios.get(route('ingreso-convocatoria-oferta', { id: convocatoria.value.id }));
            oferta.value = resp.data.oferta;
            sedes.value = oferta.value.map(({ id, title }) => ({ id, title }));
        }

        if (step.value === 3 && sede.value != null) {
        }
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
            <v-stepper-item :value="1" :title="$t('ingreso._seleccion_convocatoria_')" :bg-color="step === 1 ? 'primary' : ''"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :value="2" :title="$t('sede._sede_')"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :value="3" :title="$t('ingreso._seleccion_carreras_')"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :value="4" :title="$t('ingreso._resumen_')"></v-stepper-item>
        </v-stepper-header>
        <v-stepper-window>
            <v-stepper-window-item :value="1">
                <v-card :title="$t('ingreso._seleccion_convocatoria_indicacion_')" flat>
                    <v-card-text class="pt-4">
                        <v-form fast-fail ref="form1">
                            <v-autocomplete
                                clearable
                                :label="$t('convocatoria._convocatoria_')"
                                :items="convocatorias"
                                v-model="convocatoria"
                                return-object
                                :rules="[(v) => !!v || $t('_campo_requerido_')]"
                                item-title="nombre"
                                item-value="id"
                                prepend-icon="mdi-form-dropdown"
                            ></v-autocomplete>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn @click="nextStep" rounded variant="tonal" color="primary" append-icon="mdi-chevron-right">
                            {{ $t('_siguiente_') }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-stepper-window-item>

            <v-stepper-window-item :value="2">
                <v-card :title="$t('ingreso._seleccion_sede_indicacion_')" flat>
                    <v-card-text class="pt-4">
                        <v-form fast-fail ref="form2">
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
            <v-stepper-window-item :value="4">
                <v-card :title="$t('ingreso._resumen_')" flat>
                    <v-card-text class="pt-4">
                        {{ $t('ingreso._revise_informacion_') }}
                        <v-alert border="start" type="info" variant="outlined">
                            <template v-slot:title> {{ $t('ingreso._seleccion_convocatoria_') }} </template>

                            <span class="text-h6 text-black">{{ convocatoria.nombre }} -- {{ convocatoria.descripcion }}</span>
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
