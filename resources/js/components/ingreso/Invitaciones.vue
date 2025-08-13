<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { reactive, ref } from 'vue';
import type { VForm } from 'vuetify/components';

const formRef = ref<VForm | null>(null);
// Datos del formulario
const form = reactive({
    tipoInvitacion: null,
    tipoEnvio: null,
});
const loading = ref(false);

const tipoEnvioRules: ((value: string) => true | string)[] = [(v) => !!v || 'Elija un tipo de envío'];
const tipoInvitacionRules: ((value: string) => true | string)[] = [(v) => !!v || 'Elija la forma de invitación'];

const opciones = [
    { value: 'todos', label: 'Todos los bachilleres en la base de datos' },
    { value: 'opciones', label: 'Solo a estudiantes de bachilleratos que son requisitos para las carreras universitarias' },
];
const opcionesTipoInvitacion = [
    { value: 'nuevo', label: 'Solo invitaciones que no se hayan enviado antes' },
    { value: 'reenvio', label: 'Reenviar invitación si ya fue enviada' },
];

/*async function enviarInvitaciones() {
    const valid = formRef.value?.validate();
    console.log(valid);
    if (valid) {
        try {
            const response = await axios.post(route('ingreso-bachillerato-candidatos-invitaciones'), {
                tipoEnvio: form.tipoEnvio,
                tipoInvitacion: form.tipoInvitacion,
            });
        } catch (error) {
            console.error(error);
        }
    }
}*/

async function submitForm(): Promise<void> {
    const result = await formRef.value?.validate();

    if (result?.valid) {
        loading.value = true;
        axios
            .post(route('ingreso-bachillerato-candidatos-invitaciones'), {
                tipoEnvio: form.tipoEnvio,
                tipoInvitacion: form.tipoInvitacion,
            })
            .then(function (response) {
                Swal.fire({
                    title: 'Enviado',
                    position: 'top-end',
                    text: 'Invitaciones enviadas correctamente',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500,
                });
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(function () {
                loading.value = false;
            });
    }
}
</script>
<template>
    <v-card class="mx-auto my-8" elevation="16">
        <v-card-item>
            <v-card-title> Enviar invitaciones a bachilleres </v-card-title>

            <v-card-subtitle>
                Las invitaciones solo se mandarán a estudiantes de bachillerato que tengan correo electrónico registrado
            </v-card-subtitle>
        </v-card-item>

        <v-card-text>
            <v-form @submit.prevent="submitForm" ref="formRef">
                <div>
                    <v-select
                        label="Enviar invitaciones a:"
                        :rules="tipoEnvioRules"
                        :items="opciones"
                        :item-title="'label'"
                        v-model="form.tipoEnvio"
                    ></v-select>
                    <v-select
                        label="Tipo invitación"
                        :rules="tipoInvitacionRules"
                        :items="opcionesTipoInvitacion"
                        :item-title="'label'"
                        v-model="form.tipoInvitacion"
                    ></v-select>
                </div>
                <v-btn :loading="loading" rounded variant="tonal" color="blue-darken-4" append-icon="mdi-email-arrow-right-outline" type="submit"
                    >Enviar</v-btn
                >
            </v-form>
            <v-overlay v-model="loading" persistent class="align-center justify-center" contained>
                <v-progress-circular color="primary" size="64" indeterminate></v-progress-circular>
            </v-overlay>
        </v-card-text>
    </v-card>
</template>
