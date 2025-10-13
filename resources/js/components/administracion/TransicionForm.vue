<script setup lang="ts">
import axios from 'axios';
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
import Swal from 'sweetalert2';
import { onMounted, ref, toRef } from 'vue';
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
    indicaciones: string;
    flujo_id: number | null;
}

const props = defineProps(['item', 'accion', 'flujos']);
const content = ref(props.item?.indicaciones);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    indicaciones: '',
    flujo_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    formData.value.indicaciones = content.value;
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.post(route('proceso-etapa-save'), formData.value);
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

onMounted(() => {
    reset();
    if (props.accion === 'edit') {
        formData.value = { ...props.item };
    }
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
    <v-card :title="`${isEditing ? $t('etapa._editar_') : $t('etapa._crear_')} `">
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
                                (v) => (!!v && v.length <= 100) || $t('_longitud_maxima_') + ': 100 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('etapa._codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[(v) => !v || v.length <= 255 || $t('_longitud_maxima_') + ': 255 ' + $t('_caracteres_')]"
                            counter="255"
                            :label="$t('etapa._nombre_') + ' *'"
                        ></v-text-field>

                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            required
                            :label="$t('flujo._singular_') + ' *'"
                            :items="flujos"
                            v-model="formData.flujo_id"
                            item-title="codigo"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-col>
                    <v-col cols="12" class="pl-15">
                        {{ $t('etapa._indicaciones_') }}
                        <el-tiptap v-model:content="content" :extensions="extensions" />
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
