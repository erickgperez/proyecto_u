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
    nombre: string;
    distrito_id: number | null;
}

const props = defineProps(['item', 'accion', 'distritos']);

const formData: FormData = reactive({
    id: null,
    codigo: '',
    nombre: '',
    distrito_id: null,
});
const isEditing = toRef(() => !!formData.id);

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.postForm(route('academica-sede-save'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
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
            }
        } catch (error: any) {
            console.log(error.response.data.message);
            Swal.fire({
                title: t('_error_'),
                text: t('_no_se_pudo_guardar_formulario_'),
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
        formData.id = props.item.id;
        formData.codigo = props.item.codigo;
        formData.nombre = props.item.nombre;
        formData.distrito_id = props.item.distrito_id;
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
                                (v) => (!!v && v.length <= 255) || $t('_longitud_maxima') + ': 100 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('_nombre_') + ' *'"
                        ></v-text-field>

                        <v-autocomplete
                            :label="$t('_distrito_') + ' *'"
                            :items="props.distritos"
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
