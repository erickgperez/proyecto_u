<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref } from 'vue';
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
    carrera_principal_id: number | null;
    sede_principal_id: number | null;
    carrera_secundaria_id: number | null;
    sede_secundaria_id: number | null;
}

const props = defineProps(['persona', 'aspirante']);

const formData = ref<FormData>({
    convocatoria_id: null,
    carrera_principal_id: null,
    sede_principal_id: null,
    carrera_secundaria_id: null,
    sede_secundaria_id: null,
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
            sedes.value = response.data.sedes;
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});

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
    }
}

function prevStep() {
    step.value--;
}
</script>
<template>
    <v-stepper alt-labels hide-actions v-model="step">
        <v-stepper-header>
            <v-stepper-item :value="1" title="Selección convocatoria" :bg-color="step === 1 ? 'primary' : ''"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :value="2" title="Carrera principal"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :value="3" title="Carrera alternativa 1"></v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :value="4" title="Carrera alternativa 2"></v-stepper-item>
        </v-stepper-header>
        <v-stepper-window>
            <v-stepper-window-item :value="1">
                <v-card title="Seleccione la convocatoria en la que participará" flat>
                    <v-form fast-fail ref="form1">
                        <v-autocomplete
                            clearable
                            :label="$t('_convocatoria_')"
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
                <v-card title="Carrera en la que desea ser seleccionado como primera opción" flat>
                    <v-form fast-fail ref="form2">
                        <v-autocomplete
                            clearable
                            :label="$t('_carrera_principal_')"
                            :items="carreras"
                            v-model="formData.carrera_principal_id"
                            item-title="nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>

                        <v-autocomplete
                            clearable
                            :label="$t('_sede_principal_')"
                            :items="sedes"
                            v-model="formData.sede_principal_id"
                            item-title="nombreCompleto"
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
                <v-card title="Carrera alternativa, en caso de no ser seleccionado en la carrera principal" flat>
                    <v-form fast-fail ref="form3">
                        <v-autocomplete
                            clearable
                            :label="$t('_carrera_alternativa_')"
                            :items="carreras"
                            v-model="formData.carrera_alternativa_id"
                            item-title="nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>

                        <v-autocomplete
                            clearable
                            :label="$t('_sede_alternativa_')"
                            :items="sedes"
                            v-model="formData.sede_alternativa_id"
                            item-title="nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-form>
                    <v-btn class="me-4" rounded variant="tonal" color="secundary" prepend-icon="mdi-chevron-left" @click="prevStep">
                        {{ $t('_atras_') }}
                    </v-btn>
                    <v-btn @click="nextStep" rounded variant="tonal" color="primary" append-icon="mdi-chevron-right">{{ $t('_siguiente_') }}</v-btn>
                </v-card>
            </v-stepper-window-item>
            <v-stepper-window-item :value="4">
                <v-card title="Segunda alternativa, en caso de no ser seleccionado en ninguna de las carreras anteriores" flat>
                    <v-form fast-fail ref="form4"></v-form>
                    <v-btn class="me-4" rounded variant="tonal" color="secundary" prepend-icon="mdi-chevron-left" @click="prevStep">
                        {{ $t('_atras_') }}
                    </v-btn>
                    <v-col cols="12" align="right">
                        <v-btn :loading="loading" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                            {{ $t('_guardar_') }}
                        </v-btn>
                    </v-col>

                    <!-- <v-btn color="primary" @click="nextStep">Next</v-btn> -->
                </v-card>
            </v-stepper-window-item>
        </v-stepper-window>
    </v-stepper>
</template>
