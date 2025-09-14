<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, reactive, ref, toRef, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    formRef.value!.reset();
}

interface FormData {
    id: number | null;
    codigo: string;
    descripcion: string;
    grado_id: number | null;
}

const props = defineProps(['item', 'accion', 'grados']);

let formData: FormData = reactive({
    id: null,
    codigo: '',
    descripcion: '',
    grado_id: null,
});
const isEditing = toRef(() => !!formData.id);

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.postForm(route('plan_estudio-tipo_carrera-save'), formData);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);

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
            console.log(error.response.data.message);
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
    reset();
    if (props.accion === 'edit') {
        formData = { ...props.item };
    }
});

watch(
    () => props.accion,
    (newValue) => {
        if (newValue == 'new') {
            reset();
        }
    },
);
</script>
<template>
    <v-card :title="`${isEditing ? $t('tipoCarrera._editar_tipo_carrera_') : $t('tipoCarrera._crear_tipo_carrera_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => !v || v.length <= 20 || $t('_longitud_maxima_') + ': 20 ' + $t('_caracteres_'),
                            ]"
                            counter="20"
                            :label="$t('_codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.descripcion"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => (!!v && v.length <= 50) || $t('_longitud_maxima') + ': 50 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('_descripcion_') + ' *'"
                        ></v-text-field>

                        <v-autocomplete
                            clearable
                            :label="$t('grado._grado_')"
                            :items="props.grados"
                            v-model="formData.grado_id"
                            item-title="descripciones"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
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
