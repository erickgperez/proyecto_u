<script setup lang="ts">
import { useFilteredMerge } from '@/composables/useFilteredMerge';
import { useFunciones } from '@/composables/useFunciones';
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
import { computed, onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { filteredAssign } = useFilteredMerge();
const { rules, mensajeExito, mensajeError } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
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

    if (valid) {
        try {
            const resp = await axios.post(route('proceso-etapa-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();

    if (props.item) {
        filteredAssign(formData.value, props.item);
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

const titleForm = computed(() => {
    return isEditing.value ? t('etapa._editar_') : t('etapa._crear_');
});
</script>
<template>
    <v-card class="rounded-t-xl">
        <v-card-title class="border-b-md bg-blue-grey-lighten-3">
            {{ titleForm }}
        </v-card-title>
        <v-card-text class="pt-4">
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[rules.required, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('etapa._codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[rules.required, rules.maxLength(255)]"
                            counter="255"
                            :label="$t('etapa._nombre_') + ' *'"
                        ></v-text-field>

                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            :rules="[rules.required]"
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
        </v-card-text>
    </v-card>
</template>
