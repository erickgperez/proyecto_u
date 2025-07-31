<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const form = useForm({
    archivo: null,
});

const rules = [
    (value: any) => {
        if (value) return true;
        return 'Debe elegir un archivo de excel';
    },
];

function submit() {
    form.post(route('ingreso-import-data-bachillerato'), {
        onSuccess: (page) => {
            form.reset();

            Swal.fire({
                title: 'Éxito',
                text: 'Datos subidos correctamente',
                icon: 'success',
                confirmButtonColor: '#D7E1EE',
            });
        },
        onError: (errors) => {
            Swal.fire({
                title: 'Error',
                text: 'Verifique que ha subido un archivo CSV, con {tabulador} como separador de campo ',
                icon: 'error',
                confirmButtonColor: '#D7E1EE',
            });
        },
    });
}
</script>

<template>
    <Head title="Cargar datos "></Head>
    <AppLayout>
        <v-sheet class="mx-auto mt-10" width="300">
            <v-form fast-fail @submit.prevent="submit">
                <v-file-input
                    label="Subir un archivo CSV"
                    accept=".csv"
                    clearable
                    persistent-hint
                    @input="form.archivo = $event.target.files[0]"
                    :rules="rules"
                ></v-file-input>
                <v-btn :loading="form.processing" class="mt-2" type="submit" block rounded variant="tonal" color="blue-darken-4">Enviar</v-btn>
            </v-form>
        </v-sheet>
    </AppLayout>
</template>
