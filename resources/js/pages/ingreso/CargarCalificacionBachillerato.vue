<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { reactive, ref } from 'vue';
import type { VForm } from 'vuetify/components';

const { hasPermission } = usePermissions();

import { useFunciones } from '@/composables/useFunciones';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { mensajeError, mensajeExito } = useFunciones();

const formRef = ref<VForm | null>(null);
interface FormData {
    archivo: File | null;
    tipoCarga: string;
}

const formData: FormData = reactive({
    archivo: null,
    tipoCarga: 'incremental',
});
const loading = ref(false);

const tipoCarga = [
    { value: 'nueva', title: t('_carga_completa_borrar_anteriores_') },
    { value: 'incremental', title: t('_cargar_solo_datos_nuevos_') },
];

const submitForm = async () => {
    const { valid } = await formRef.value!.validate();
    loading.value = true;
    if (valid) {
        try {
            await axios.post(route('ingreso-import-calificacion-bachillerato'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            formRef.value!.reset();
            formData.archivo = null;
            formData.tipoCarga = 'incremental';

            mensajeExito(t('_datos_subidos_correctamente_'));
        } catch (error) {
            mensajeError(t('_verifique_que_ha_subido_archivo_csv_'));
        }
    }
    loading.value = false;
};
</script>

<template>
    <Head :title="$t('ingreso._cargar_calificaciones_')"></Head>
    <AppLayout
        :titulo="$t('ingreso._cargar_calificaciones_')"
        :subtitulo="$t('ingreso._suba_archivo_calificaciones_estudiantes_bachillerato_')"
        icono="mdi-card-account-details-star-outline"
    >
        <v-sheet v-if="hasPermission('MENU_INGRESO_CALIFICACION-BACHILLERATO')" class="elevation-12 pa-2 mx-auto rounded-xl" max-width="600">
            <h6 class="pa-3">{{ $t('ingreso._cargar_calificaciones_indicaciones_') }}</h6>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-file-input
                    :label="$t('_subir_archivo_csv_')"
                    accept=".csv,.txt"
                    clearable
                    v-model="formData.archivo"
                    @input="formData.archivo = $event.target.files[0]"
                    :rules="[(v) => !!v || $t('_debe_elegir_archivo_')]"
                    show-size
                    counter
                ></v-file-input>
                <!-- <v-select
                    :label="$t('_tipo_carga_')"
                    :rules="[(v) => !!v || $t('_elija_tipo_carga_')]"
                    :items="tipoCarga"
                    v-model="formData.tipoCarga"
                    prepend-icon="mdi-form-dropdown"
                ></v-select> -->
                <div class="d-flex align-center justify-center">
                    <v-btn :loading="loading" class="mt-2" type="submit" append-icon="mdi-upload" rounded variant="tonal" color="blue-darken-4">{{
                        $t('_enviar_')
                    }}</v-btn>
                </div>
            </v-form>
        </v-sheet>
        <v-alert v-else border="top" type="warning" variant="outlined" prominent>
            {{ $t('_no_tiene_permiso_para_esta_accion_') }}
        </v-alert>
    </AppLayout>
</template>
