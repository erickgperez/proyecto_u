<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

import { useFunciones } from '@/composables/useFunciones';
import {
    Bold,
    BulletList,
    Color,
    Document,
    FormatClear,
    //FontSize,
    // necessary extensions
    //Heading,
    History,
    Image,
    Indent,
    Italic,
    OrderedList,
    Paragraph,
    Strike,
    Text,
    TextAlign,
    Underline,
} from 'element-tiptap';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
    formData.value.afiche = null;
}

interface FormData {
    id: number | null;
    nombre: string;
    activa: boolean;
    descripcion: string;
    flujo_id: number | null;
    cuerpo_mensaje: string;
    afiche: string | null;
    afiche_file: File | null;
}

const props = defineProps(['item', 'accion', 'flujos']);

const content = ref(props.item?.cuerpo_mensaje);

const formData = ref<FormData>({
    id: null,
    nombre: '',
    activa: true,
    descripcion: '',
    flujo_id: null,
    cuerpo_mensaje: '',
    afiche_file: null,
    afiche: '',
});
const isEditing = toRef(() => props.accion === 'edit');

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

    if (valid) {
        formData.value.cuerpo_mensaje = content.value;
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
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            const msj = axios.isAxiosError(error) ? error.response.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();

    formData.value = { ...props.item };
});

// editor extensions
const extensions = [
    Document,
    Text,
    Paragraph,
    //Heading.configure({ levels: [1, 2, 3] }),
    //FontSize,
    Bold,
    Underline,
    Italic,
    Strike,
    TextAlign,
    BulletList,
    OrderedList,
    Indent,
    Color,
    Image.configure({ allowBase64: true }),
    FormatClear,
    History,
];
</script>
<template>
    <v-card :title="`${isEditing ? $t('_editar_convocatoria_') : $t('_crear_convocatoria_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[rules.required, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('_nombre_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.descripcion"
                            :rules="[rules.maxLength(255)]"
                            counter="255"
                            :label="$t('_descripcion_')"
                        ></v-text-field>
                        <v-checkbox v-model="formData.activa" :label="$t('convocatoria._activa_')"></v-checkbox>
                        <v-select
                            required
                            :rules="[rules.required]"
                            icon-color="deep-orange"
                            :label="$t('convocatoria._flujo_')"
                            :items="props.flujos"
                            v-model="formData.flujo_id"
                            item-title="nombre"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                            :hint="$t('convocatoria._flujo_hint_')"
                            persistent-hint
                        ></v-select>
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
                    <v-col cols="12" class="pl-15">
                        {{ $t('_cuerpo_mensaje_') }}
                        <el-tiptap v-model:content="content" :extensions="extensions" />

                        {{ $t('_consejo_cuerpo_mensaje_') }}
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
<style lang="css" scoped>
.el-tiptap-editor__menu-bar {
    /*position: sticky;*/
    /*background-color: #f0f0f0;
    border-bottom: 1px solid #ccc;
    z-index: 10;*/
    position: sticky;
    top: 0; /* The element will stick to the top of the viewport when its top edge reaches 0 pixels from the viewport's top */
    background-color: lightblue;
}
/*.ProseMirror {
    padding-top: 40px;
}*/
</style>
