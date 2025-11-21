<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import PersonaDatosContactoForm from '../administracion/PersonaDatosContactoForm.vue';

const { t } = useI18n();

const emit = defineEmits(['form-saved', 'siguienteEtapa']);

const step = ref(1);
const snackbar = ref(false);
const text = ref('');
const form1 = ref(null);
const distritosTree = ref([]);

const form1Data = ref({
    fecha_nacimiento: '',
});

const props = defineProps(['solicitud']);
const persona = ref(null);

onMounted(() => {
    form1Data.value.fecha_nacimiento = props.solicitud.solicitante.persona.fecha_nacimiento;

    axios
        .get(route('administracion-persona-info', { id: props.solicitud.solicitante.persona.uuid }))
        .then(function (response) {
            distritosTree.value = response.data.distritosTree;
            persona.value = response.data.persona;
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});

function handleFormSave() {
    //emit('form-saved');
    step.value++;
}

async function submitForm1() {
    const { valid } = await form1.value.validate();
    if (valid) {
        axios
            .post(route('workflow-ingreso-solicitud-persona-save', { idPersona: props.solicitud.solicitante.persona_id }), form1Data.value)
            .then(function (response) {
                if (response.data.status === 'ok') {
                    step.value++;
                } else {
                    throw new Error(response.data.message);
                }
            })
            .catch(function (error) {
                Swal.fire({
                    title: t('_error_'),
                    text: t('_no_se_pudo_guardar_formulario_') + '. ' + error.message,
                    icon: 'error',
                    confirmButtonColor: '#D7E1EE',
                });
                console.error('Error fetching data:', error);
            });
    }
}

function prevStep() {
    step.value--;
}
</script>
<template>
    <v-stepper alt-labels hide-actions v-model="step">
        <v-stepper-header>
            <v-stepper-item :value="1" :color="step === 1 ? 'indigo' : ''">
                <span :class="step === 1 ? 'text-indigo' : ''">{{ $t('persona._datos_personales_') }}</span>
            </v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :color="step === 2 ? 'indigo' : ''" :value="2">
                <span :class="step === 2 ? 'text-indigo' : ''">{{ $t('persona._datos_contacto_') }}</span>
            </v-stepper-item>
            <v-divider></v-divider>
            <v-stepper-item :color="step === 3 ? 'indigo' : ''" :value="3">
                <span :class="step === 3 ? 'text-indigo' : ''">{{ $t('solicitud._finalizar_') }}</span>
            </v-stepper-item>
            <v-divider></v-divider>
        </v-stepper-header>
        <v-stepper-window>
            <v-stepper-window-item :value="1">
                <v-card flat>
                    <v-form fast-fail ref="form1" @submit.prevent="submitForm1">
                        <v-card-text class="pt-4">
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        readonly
                                        :label="$t('persona._primer_nombre_')"
                                        :model-value="solicitud.solicitante.persona.primer_nombre"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        readonly
                                        :label="$t('persona._segundo_nombre_')"
                                        :model-value="solicitud.solicitante.persona.segundo_nombre"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        readonly
                                        :label="$t('persona._tercer_nombre_')"
                                        :model-value="solicitud.solicitante.persona.tercer_nombre"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        readonly
                                        :label="$t('persona._primer_apellido_')"
                                        :model-value="solicitud.solicitante.persona.primer_apellido"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        readonly
                                        :label="$t('persona._segundo_apellido_')"
                                        :model-value="solicitud.solicitante.persona.segundo_apellido"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        readonly
                                        :label="$t('persona._primer_apellido_')"
                                        :model-value="solicitud.solicitante.persona.tercer_apellido"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        readonly
                                        :label="$t('persona._sexo_')"
                                        :model-value="solicitud.solicitante.persona.sexo.descripcion"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-locale-provider locale="es">
                                        <v-date-input
                                            icon-color="deep-orange"
                                            clearable
                                            required
                                            :rules="[(v) => !!v || $t('_campo_requerido_')]"
                                            v-model="form1Data.fecha_nacimiento"
                                            :label="$t('persona._fecha_nacimiento_') + ' *'"
                                        ></v-date-input>
                                    </v-locale-provider>
                                </v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn type="submit" rounded variant="tonal" color="primary" append-icon="mdi-chevron-right">
                                {{ $t('_siguiente_') }}
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-stepper-window-item>

            <v-stepper-window-item :value="2">
                <PersonaDatosContactoForm
                    :item="persona"
                    :accion="'datos-contacto'"
                    :distritosTree="distritosTree"
                    :guardarTxt="$t('_siguiente_')"
                    @form-saved="handleFormSave"
                >
                </PersonaDatosContactoForm>
                <v-btn class="mt-4" rounded variant="tonal" color="secundary" prepend-icon="mdi-chevron-left" @click="prevStep">
                    {{ $t('_atras_') }}
                </v-btn>
            </v-stepper-window-item>
            <v-stepper-window-item :value="3">
                <span class="text-h6">{{ $t('solicitud._confirmar_siguiente_etapa_') }}</span>
                <v-divider class="mb-6"></v-divider>
                <v-btn color="primary" @click="emit('siguienteEtapa')">{{ $t('solicitud._pasar_siguiente_etapa_') }}</v-btn>
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
