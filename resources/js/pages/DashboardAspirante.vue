<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref, shallowRef } from 'vue';

const page = usePage();
const persona = page.props.auth.persona;

const finished = shallowRef(false);

const solicitud = ref(null);

function crearSolicitud() {
    axios
        .get(route('ingreso-aspirante-solicitud-crear', { idPersona: persona.id }))
        .then(function (response) {
            solicitud.value = response.data.solicitud;
            console.log(response.data);
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
}

onMounted(() => {
    axios
        .get(route('ingreso-aspirante-solicitud', { idPersona: persona.id }))
        .then(function (response) {
            solicitud.value = response.data.solicitud;
            console.log(response.data);
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});
</script>

<template>
    <v-alert
        v-if="solicitud == null"
        :text="$t('solicitud._mensaje_crear_solicitud_')"
        :title="$t('solicitud._iniciar_solicitud_')"
        border="top"
        border-color="info"
        elevation="2"
        type="info"
        variant="outlined"
    >
        <v-spacer class="mb-5"></v-spacer>

        <v-btn prepend-icon="mdi-invoice-text-plus-outline" rounded variant="tonal" color="primary" @click="crearSolicitud">
            {{ $t('solicitud._crear_solicitud_') }}
        </v-btn>
    </v-alert>
    <v-stepper-vertical :items="['Paso 1', 'Paso 2', 'Paso 3']" v-if="solicitud != null">
        <template v-slot:item.1="{ step }"> Hola </template>
        <template v-slot:next="{ next }">
            <v-btn color="primary" @click="next"></v-btn>
        </template>
        <template v-slot:prev="{ prev }">
            <v-btn v-if="!finished" variant="plain" @click="prev"></v-btn>

            <v-btn v-else text="Reset" variant="plain" @click="finished = false"></v-btn>
        </template>
    </v-stepper-vertical>
</template>
