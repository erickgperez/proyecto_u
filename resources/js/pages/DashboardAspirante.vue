<script setup lang="ts">
import SeleccionProcesoForm from '@/components/ingreso/SeleccionProcesoForm.vue';
import { Etapa } from '@/types/tipos';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const page = usePage();
const persona = page.props.auth.persona;

const solicitud = ref(null);
const aspirante = ref(null);

function crearSolicitud() {
    axios
        .get(route('ingreso-aspirante-solicitud-crear', { id: aspirante.value?.id }))
        .then(function (response) {
            actualizar(response.data);
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
            actualizar(response.data);
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});

function actualizar(data: any) {
    solicitud.value = data.solicitud;
    etapas.value = data.etapas;
    aspirante.value = data.aspirante;

    const index = etapas.value.findIndex((item) => item.id === data.solicitud.etapa_id);

    step.value = index + 1;
}

const step = ref(1);

const etapas = ref<Etapa[]>([]);
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
    <v-stepper-vertical :items="etapas.map((s) => s.codigo)" v-if="solicitud != null" v-model="step">
        <template v-for="(s, index) in etapas" v-slot:[`item.${index+1}`] :key="s.codigo">
            <h3>{{ s.nombre }}</h3>

            <p>{{ s.indicaciones }}</p>

            <SeleccionProcesoForm
                :solicitud="solicitud"
                @form-saved="actualizar"
                :aspirante="aspirante"
                v-if="s.codigo == 'SELECCION_PROCESO_CARRERA'"
            ></SeleccionProcesoForm>
        </template>
    </v-stepper-vertical>
</template>
