<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();

const loading = ref(false);
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
            console.log(response.data);

            convocatorias.value = response.data.convocatorias;
            carreras.value = response.data.carreras;
            sedes.value = response.data.sedes;
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});
</script>
<template>
    <v-card>
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-autocomplete
                        clearable
                        :label="$t('_convocatoria_')"
                        :items="convocatorias"
                        v-model="formData.convocatoria_id"
                        item-title="nombre"
                        item-value="id"
                        prepend-icon="mdi-form-dropdown"
                    ></v-autocomplete>

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
