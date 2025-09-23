<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { computed, onMounted, ref, toRef } from 'vue';
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
    nombre: string;
    distrito_id: number | null;
    municipio_id: number | null;
    departamento_id: number | null;
}

const props = defineProps(['item', 'accion', 'distritos', 'departamentos', 'municipios']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    distrito_id: null,
    municipio_id: null,
    departamento_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.post(route('academica-sede-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);
                //localItem.value.afiche = resp.data.convocatoria.afiche;
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

const municipiosFiltrados = computed(() => {
    if (!formData.value.departamento_id) {
        return []; // No hay departamento seleccionado
    }
    return props.municipios.filter(
        (municipio: any) => municipio.departamento_id === formData.value.departamento_id || municipio.id === formData.value.municipio_id,
    );
});

const distritosFiltrados = computed(() => {
    if (!formData.value.municipio_id) {
        return []; // No hay municipio seleccionado
    }
    return props.distritos.filter(
        (distrito: any) => distrito.municipio_id === formData.value.municipio_id || distrito.id === formData.value.distrito_id,
    );
});

onMounted(() => {
    reset();
    if (props.accion === 'edit') {
        formData.value = { ...props.item };
    }
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('_editar_sede_') : $t('_crear_sede_')} `">
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
                            v-model="formData.nombre"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => (!!v && v.length <= 255) || $t('_longitud_maxima') + ': 255 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('_nombre_') + ' *'"
                        ></v-text-field>

                        <v-autocomplete
                            clearable
                            :label="$t('_departamento_')"
                            :items="props.departamentos"
                            v-model="formData.departamento_id"
                            item-title="descripcion"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>

                        <v-autocomplete
                            clearable
                            :label="$t('_municipio_')"
                            :items="municipiosFiltrados"
                            v-model="formData.municipio_id"
                            item-title="descripcion"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>

                        <v-autocomplete
                            clearable
                            :label="$t('_distrito_') + ' *'"
                            :items="distritosFiltrados"
                            v-model="formData.distrito_id"
                            :rules="[(v) => !!v || $t('_campo_requerido_')]"
                            item-title="descripcion"
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
