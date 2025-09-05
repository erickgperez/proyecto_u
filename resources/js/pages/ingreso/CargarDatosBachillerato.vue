<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { reactive, ref } from 'vue';
import type { VForm } from 'vuetify/components';

import { useI18n } from 'vue-i18n';

const { t } = useI18n();

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
            await axios.post(route('ingreso-import-data-bachillerato'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            formRef.value!.reset();
            formData.archivo = null;
            formData.tipoCarga = 'incremental';

            Swal.fire({
                title: t('_exito_'),
                text: t('_datos_subidos_correctamente_'),
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                toast: true,
            });
        } catch (error) {
            Swal.fire({
                title: t('_error_'),
                text: t('_verifique_que_ha_subido_archivo_csv_'),
                icon: 'error',
                confirmButtonColor: '#D7E1EE',
            });
        }
    }
    loading.value = false;
};
</script>

<template>
    <Head :title="$t('_cargar_datos_')"></Head>
    <AppLayout :titulo="$t('_carga_archivo_datos_')" :subtitulo="$t('_suba_archivo_datos_estudiantes_bachillerato_')" icono="mdi-email-fast-outline">
        <v-sheet class="pa-5 mx-auto" width="400">
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-file-input
                    :label="$t('_subir_archivo_csv_')"
                    accept=".csv"
                    clearable
                    v-model="formData.archivo"
                    @input="formData.archivo = $event.target.files[0]"
                    :rules="[(v) => !!v || $t('_debe_elegir_archivo_')]"
                    show-size
                    counter
                ></v-file-input>
                <v-select
                    :label="$t('_tipo_carga_')"
                    :rules="[(v) => !!v || $t('_elija_tipo_carga_')]"
                    :items="tipoCarga"
                    v-model="formData.tipoCarga"
                ></v-select>
                <v-btn :loading="loading" class="mt-2" type="submit" block rounded variant="tonal" color="blue-darken-4">{{ $t('_enviar_') }}</v-btn>
            </v-form>
        </v-sheet>
    </AppLayout>
</template>
