<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';

const tipoInvitacion = ref(null);
const tipoEnvio = ref(null);
const opciones = [
    { value: 'todos', label: 'Todos los bachilleres en la base de datos' },
    { value: 'opciones', label: 'Solo a estudiantes de bachilleratos que son requisitos para las carreras universitarias' },
];
const opcionesTipoInvitacion = [
    { value: 'nuevo', label: 'Solo invitaciones que no se hayan enviado antes' },
    { value: 'reenvio', label: 'Reenviar invitación si ya fue enviada' },
];

async function enviarInvitaciones() {
    try {
        const response = await axios.post(route('ingreso-bachillerato-candidatos-invitaciones'), {
            tipoEnvio: tipoEnvio.value,
            tipoInvitacion: tipoInvitacion.value,
        });
    } catch (error) {
        console.error(error);
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
            <form @submit.prevent="enviarInvitaciones">
                <div>
                    <v-select label="Enviar invitaciones a:" :items="opciones" :item-title="'label'" v-model="tipoEnvio"></v-select>
                    <v-select label="Tipo invitación" :items="opcionesTipoInvitacion" :item-title="'label'" v-model="tipoInvitacion"></v-select>
                </div>
                <v-btn rounded variant="tonal" color="blue-darken-4" append-icon="mdi-email-arrow-right-outline" @click="enviarInvitaciones"
                    >Enviar</v-btn
                >
            </form>
        </v-card-text>
    </v-card>
</template>
