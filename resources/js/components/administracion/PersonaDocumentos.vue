<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
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

    if (valid) {
        try {
            const resp = await axios.postForm(route('administracion-documento-save'), formData.value, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            if (resp.data.status == 'ok') {
                //if (!isEditing.value) {
                reset();
                //}
                //emit('form-saved', resp.data.item);
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

const tab = ref(null);
const documentos = ref([]);

onMounted(() => {
    reset();

    //formData.value = { ...props.item };
    formData.value.persona_id = props.item.id;

    // Recuperar los documentos de la persona
    axios
        .get(route('administracion-persona-documentos', { id: props.item.uuid }))
        .then(function (response) {
            if (response.data.status === 'ok') {
                documentos.value = response.data.documentos;
            }
        })
        .catch(function (error) {
            console.log(error);
        });
});
</script>
<template>
    <v-card>
        <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
            <v-tab value="1">{{ $t('documento._plural_') }}</v-tab>
            <v-tab value="2">{{ $t('documento._crear_') }}</v-tab>
        </v-tabs>

        <v-tabs-window v-model="tab">
            <v-tabs-window-item value="1">
                <v-card class="pa-4 mb-8" max-width="300" v-for="doc in documentos" :key="doc.id">
                    <!--<v-img height="200px" src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg" cover></v-img>-->

                    <v-card-title> {{ doc.tipo.codigo }} </v-card-title>

                    <v-card-subtitle> {{ doc.archivos[0].tipo }}</v-card-subtitle>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            :text="$t('_descargar_')"
                            :href="`/administracion/documento/${doc.uuid}/descargar`"
                            target="_blank"
                        ></v-btn>
                    </v-card-actions>
                </v-card>
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
                                    :items="props.tiposDocumento"
                                    v-model="formData.tipo_id"
                                    item-title="descripcion"
                                    item-value="id"
                                    prepend-icon="mdi-form-dropdown"
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
