<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, toRef, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    formRef.value!.reset();
    formData.value.afiche = null;
}

interface FormData {
    id: number | null;
    nombre: string;
    descripcion: string;
    fecha: Date | null;
    cuerpo_mensaje: string;
    afiche: string | null;
    afiche_file: File | null;
}

const props = defineProps(['item', 'accion']);

const formData = ref<FormData>({
    id: null,
    nombre: '',
    descripcion: '',
    fecha: null,
    cuerpo_mensaje: '',
    afiche_file: null,
    afiche: '',
});
const isEditing = toRef(() => !!formData.value.id);

async function deleteAfiche() {
    await axios.delete(route('ingreso-convocatoria-afiche-delete', { id: formData.value.id }));

    Swal.fire({
        title: t('_exito_'),
        text: t('_archivo_borrado_correctamente_'),
        icon: 'success',
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        toast: true,
    });
    formData.value.afiche_file = null;
}

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.postForm(route('ingreso-convocatoria-save'), formData.value, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.convocatoria);
                formData.value.afiche = resp.data.convocatoria.afiche;
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
    <v-card :title="`${isEditing ? $t('_editar_convocatoria_') : $t('_crear_convocatoria_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-locale-provider locale="es">
                            <v-date-input
                                clearable
                                required
                                v-model="formData.fecha"
                                :rules="[(v) => !!v || $t('_fecha_requerida_')]"
                                :label="$t('_fecha_') + ' *'"
                            ></v-date-input>
                        </v-locale-provider>

                        <v-text-field
                            required
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[
                                (v) => !!v || $t('_nombre_requerido_'),
                                (v) => (!!v && v.length <= 100) || $t('_longitud_maxima') + ': 100 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('_nombre_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.descripcion"
                            :rules="[(v) => !v || v.length <= 255 || $t('_longitud_maxima_') + ': 255 ' + $t('_caracteres_')]"
                            counter="255"
                            :label="$t('_descripcion_')"
                        ></v-text-field>

                        <v-textarea
                            prepend-icon="mdi-form-textarea"
                            :label="$t('_cuerpo_mensaje_')"
                            v-model="formData.cuerpo_mensaje"
                            :hint="$t('_consejo_cuerpo_mensaje_')"
                            persistent-hint
                        ></v-textarea>
                    </v-col>
                    <v-col cols="12">
                        <v-file-input
                            :label="$t('_afiche_formato_pdf_')"
                            accept=".pdf"
                            clearable
                            v-model="formData.afiche_file"
                            @input="formData.afiche_file = $event.target.files[0]"
                            show-size
                            counter
                        ></v-file-input>
                        <span v-if="props.accion == 'edit' && formData.afiche != null">
                            <v-list-item color="primary" rounded="xl">
                                <template v-slot:prepend>
                                    <v-avatar color="blue">
                                        <v-btn
                                            :title="$t('_descargar_afiche_actual_')"
                                            color="white"
                                            icon="mdi-file-download-outline"
                                            variant="text"
                                            :href="`/ingreso/afiche/download/${props.item.id}`"
                                        ></v-btn>
                                    </v-avatar>
                                </template>

                                <v-list-item-title>afiche.pdf</v-list-item-title>
                                <template v-slot:append>
                                    <v-avatar color="orange-lighten-2">
                                        <v-btn
                                            :title="$t('_borrar_afiche_actual_')"
                                            icon="mdi-file-document-remove-outline"
                                            variant="text"
                                            @click="deleteAfiche"
                                        ></v-btn>
                                    </v-avatar>
                                </template>
                            </v-list-item>
                        </span>
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
