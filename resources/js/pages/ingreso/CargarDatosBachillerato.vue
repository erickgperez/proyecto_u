<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { reactive, ref } from 'vue';
import type { VForm } from 'vuetify/components';

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
    { value: 'nueva', label: 'Carga completa, borrar datos existentes de cargas anteriores' },
    { value: 'incremental', label: 'Cargar solo datos nuevos' },
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
                title: 'Éxito',
                text: 'Datos subidos correctamente',
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                toast: true,
            });
        } catch (error) {
            Swal.fire({
                title: 'Error',
                text: 'Verifique que ha subido un archivo CSV, con {tabulador} como separador de campo ',
                icon: 'error',
                confirmButtonColor: '#D7E1EE',
            });
        }
    }
    loading.value = false;
};
</script>

<template>
    <Head title="Cargar datos "></Head>
    <AppLayout
        titulo="Carga de archivo de datos"
        subtitulo="Suba un archivo csv conteniendo los datos de los estudiantes de bachillerato"
        icono="mdi-email-fast-outline"
    >
        <v-sheet class="pa-5 mx-auto" width="400">
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-file-input
                    label="Subir un archivo CSV"
                    accept=".csv"
                    clearable
                    v-model="formData.archivo"
                    @input="formData.archivo = $event.target.files[0]"
                    :rules="[(v) => !!v || 'Debe elegir un archivo']"
                    show-size
                    counter
                ></v-file-input>
                <v-select
                    label="Tipo de carga:"
                    :rules="[(v) => !!v || 'Elija el tipo de carga']"
                    :items="tipoCarga"
                    :item-title="'label'"
                    v-model="formData.tipoCarga"
                ></v-select>
                <v-btn :loading="loading" class="mt-2" type="submit" block rounded variant="tonal" color="blue-darken-4">Enviar</v-btn>
            </v-form>
        </v-sheet>
    </AppLayout>
</template>
