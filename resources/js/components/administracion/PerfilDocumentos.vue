<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);
const tab = ref('');
const documentos = ref([]);
const isEditing = ref(false);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
    formData.value.archivo_file = null;
}

interface FormData {
    id: number | null;
    numero: string;
    persona_id: number | null;
    fecha_emision: Date | null;
    fecha_expiracion: Date | null;
    tipo_id: number | null;
    archivo_file: File | null;
}

const props = defineProps(['item', 'accion', 'tiposDocumento']);

const formData = ref<FormData>({
    id: null,
    numero: '',
    persona_id: null,
    fecha_emision: null,
    fecha_expiracion: null,
    tipo_id: null,
    archivo_file: null,
});

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;
    formData.value.persona_id = props.item.id;

    if (valid) {
        try {
            const resp = await axios.postForm(route('administracion-documento-save'), formData.value, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            if (resp.data.status == 'ok') {
                reset();
                documentos.value = resp.data.documentos;
                mensajeExito(t('_datos_subidos_correctamente_'));
                tab.value = '1';
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

function editDocumento(documento) {
    console.log(documento);
    formData.value = { ...documento };
    tab.value = '2';
    isEditing.value = true;
}

const documentosPersona = computed(() => {
    return documentos.value.map((doc) => doc.tipo_id);
});

const localTiposDocumentos = computed(() => {
    return props.tiposDocumento.filter((tipoDoc) => tipoDoc.multiple || !documentosPersona.value.includes(tipoDoc.id));
});

onMounted(() => {
    reset();

    // Recuperar los documentos de la persona
    axios
        .get(route('administracion-perfil-documentos', { id: props.item.uuid }))
        .then(function (response) {
            if (response.data.status === 'ok') {
                documentos.value = response.data.documentos;
            }
        })
        .catch(function (error) {
            console.log(error);
        });
});
watch(tab, (newVal) => {
    if (newVal == '1') {
        isEditing.value = false;
        reset();
    }
});
</script>
<template>
    <v-card>
        <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
            <v-tab value="1">{{ $t('documento._plural_') }}</v-tab>
            <v-tab value="2">{{ $t('documento._agregar_') }}</v-tab>
        </v-tabs>
        <v-tabs-window v-model="tab">
            <v-tabs-window-item value="1">
                <v-row class="pt-2">
                    <v-col v-for="doc in documentos" :key="doc.id" cols="12" md="4">
                        <v-card class="pa-2" max-width="500" variant="outlined">
                            <v-card-title> {{ doc.tipo.codigo }} </v-card-title>

                            <v-card-subtitle> {{ doc.archivos[0].tipo }}</v-card-subtitle>
                            <v-card-actions>
                                <v-btn
                                    color="primary"
                                    :text="$t('_ver_')"
                                    :href="`/administracion/documento/${doc.uuid}/descargar`"
                                    target="_blank"
                                ></v-btn>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" :text="$t('_actualizar_')" @click="editDocumento(doc)"></v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-tabs-window-item>
            <v-tabs-window-item value="2">
                <v-sheet class="pa-5">
                    <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                        <v-row>
                            <v-col cols="6">
                                <v-select
                                    icon-color="deep-orange"
                                    :rules="[rules.required]"
                                    :label="$t('documento._tipo_')"
                                    :items="localTiposDocumentos"
                                    v-model="formData.tipo_id"
                                    item-title="descripcion"
                                    item-value="id"
                                    prepend-icon="mdi-form-dropdown"
                                    :readonly="isEditing"
                                    :hint="isEditing ? $t('documento._no_puede_cambiar_tipo_doc_') : ''"
                                    persistent-hint
                                ></v-select>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    prepend-icon="mdi-form-textbox"
                                    v-model="formData.numero"
                                    counter="100"
                                    :label="$t('documento._numero_') + ' *'"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="6">
                                <v-locale-provider locale="es">
                                    <v-date-input clearable v-model="formData.fecha_emision" :label="$t('documento._fecha_emision_')"></v-date-input>
                                </v-locale-provider>
                            </v-col>
                            <v-col cols="6">
                                <v-locale-provider locale="es">
                                    <v-date-input
                                        clearable
                                        v-model="formData.fecha_expiracion"
                                        :label="$t('documento._fecha_expiracion_')"
                                    ></v-date-input>
                                </v-locale-provider>
                            </v-col>
                            <v-col cols="12">
                                <v-file-input
                                    :label="$t('documento._archivo_formato_imagen_pdf_')"
                                    accept="image/*, application/pdf"
                                    :rules="[rules.required]"
                                    icon-color="deep-orange"
                                    clearable
                                    v-model="formData.archivo_file"
                                    @input="formData.archivo_file = $event.target.files[0]"
                                    show-size
                                ></v-file-input>
                            </v-col>

                            <v-col cols="12" align="right">
                                <v-btn :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                                    {{ $t('_guardar_') }}
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-sheet>
            </v-tabs-window-item>
        </v-tabs-window>
    </v-card>
</template>
