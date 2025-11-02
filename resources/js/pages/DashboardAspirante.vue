<script setup lang="ts">
import IngresoSeleccionAspirante from '@/components/workflow/IngresoSeleccionAspirante.vue';
import IngresoSeleccionCarrera from '@/components/workflow/IngresoSeleccionCarrera.vue';
import IngresoSolicitud from '@/components/workflow/IngresoSolicitud.vue';
import { Convocatoria, Etapa } from '@/types/tipos';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const page = usePage();
const persona = page.props.auth.persona;

const solicitud = ref(null);
const aspirante = ref(null);
const etapas = ref<Etapa[]>([]);
const convocatoria = ref<Convocatoria | null>(null);
const convocatorias = ref<Convocatoria[]>([]);
const step = ref(1);
const form1 = ref(null);

async function crearSolicitud() {
    const { valid } = await form1.value.validate();
    if (valid) {
        axios
            .get(route('workflow-ingreso-aspirante-convocatoria-solicitud-crear', { id: aspirante.value?.id, idConvocatoria: convocatoria.value.id }))
            .then(function (response) {
                actualizar(response.data);
            })
            .catch(function (error) {
                // handle error
                console.error('Error fetching data:', error);
            });
    }
}

onMounted(() => {
    axios
        .get(route('workflow-ingreso-solicitud', { idPersona: persona.id }))
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
    convocatoria.value = data.convocatoria;
    convocatorias.value = data.convocatorias;

    const index = etapas.value.findIndex((item) => item.id === data.solicitud.etapa_id);

    step.value = index + 1;
}
</script>

<template>
    <v-alert
        v-if="solicitud == null && convocatorias.length > 0"
        :text="$t('solicitud._mensaje_crear_solicitud_')"
        :title="$t('solicitud._iniciar_solicitud_')"
        border="top"
        border-color="info"
        elevation="2"
        type="info"
        variant="outlined"
    >
        <v-spacer class="mb-5"></v-spacer>
        <span class="text-black">Seleccione la convocatoria en la que desea participar</span>
        <v-form fast-fail ref="form1">
            <v-autocomplete
                clearable
                color="black"
                icon-color="black"
                item-color="black"
                :label="$t('convocatoria._convocatoria_')"
                :items="convocatorias"
                v-model="convocatoria"
                return-object
                :rules="[(v) => !!v || $t('_campo_requerido_')]"
                item-title="descripcion"
                item-value="id"
                prepend-icon="mdi-form-dropdown"
            ></v-autocomplete>
        </v-form>

        <v-btn prepend-icon="mdi-invoice-text-plus-outline" rounded variant="tonal" color="primary" @click="crearSolicitud">
            {{ $t('solicitud._crear_solicitud_') }}
        </v-btn>
    </v-alert>
    <v-alert
        v-if="solicitud == null && convocatorias.length == 0"
        :text="$t('convocatoria._mensaje_no_convocatorias_activas_')"
        :title="$t('solicitud._iniciar_solicitud_')"
        border="top"
        border-color="warning"
        elevation="2"
        type="warning"
        variant="outlined"
    >
        <v-spacer class="mb-5"></v-spacer>
    </v-alert>

    <v-stepper-vertical :items="etapas.map((s) => s.codigo)" v-if="solicitud != null" v-model="step">
        <template v-for="(s, index) in etapas" v-slot:[`item.${index+1}`] :key="s.codigo">
            <h3>{{ s.nombre }}</h3>

            <div v-html="s.indicaciones"></div>

            <IngresoSeleccionCarrera :solicitud="solicitud" @form-saved="actualizar" v-if="s.codigo == 'SELECCION_CARRERA'"></IngresoSeleccionCarrera>
            <IngresoSolicitud :solicitud="solicitud" @form-saved="actualizar" v-if="s.codigo == 'SOLICITUD'"></IngresoSolicitud>

            <IngresoSeleccionAspirante :solicitud="solicitud" v-if="s.codigo == 'SELECCION_ASPIRANTE'"> </IngresoSeleccionAspirante>
        </template>
    </v-stepper-vertical>
</template>
