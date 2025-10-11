<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { computed, onMounted, ref, toRef, watch } from 'vue';
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
    certificacion_de: number | null;
    tipo_carrera_id: number | null;
    sedes: [];
}

const props = defineProps(['item', 'accion', 'carreras', 'tiposCarrera', 'sedes']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    certificacion_de: null,
    tipo_carrera_id: null,
    sedes: [],
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.post(route('plan_estudio-carrera-save'), formData.value);
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

const carrerasFiltradas = computed(() => {
    return props.carreras.filter((carrera: any) => carrera.id != formData.value.id);
});

onMounted(() => {
    reset();
    if (props.accion === 'edit') {
        formData.value = { ...props.item };
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
    <v-card :title="`${isEditing ? $t('carrera._editar_') : $t('carrera._crear_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => !v || v.length <= 30 || $t('_longitud_maxima_') + ': 30 ' + $t('_caracteres_'),
                            ]"
                            counter="20"
                            :label="$t('_codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[(v) => !!v || $t('_campo_requerido_')]"
                            :label="$t('_nombre_') + ' *'"
                        ></v-text-field>

                        <v-autocomplete
                            clearable
                            :label="$t('carrera._tipo_') + ' *'"
                            :items="props.tiposCarrera"
                            v-model="formData.tipo_carrera_id"
                            :rules="[(v) => !!v || $t('_campo_requerido_')]"
                            icon-color="deep-orange"
                            item-title="descripcion"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>

                        <v-autocomplete
                            clearable
                            :label="$t('carrera._padre_')"
                            :items="carrerasFiltradas"
                            v-model="formData.certificacion_de"
                            item-title="nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>

                        <v-autocomplete
                            clearable
                            :label="$t('sede._sedes_')"
                            :items="props.sedes"
                            v-model="formData.sedes"
                            item-title="nombre"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                            multiple
                            chips
                            :hint="$t('carrera._sedes_ayuda_')"
                            persistent-hint
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
