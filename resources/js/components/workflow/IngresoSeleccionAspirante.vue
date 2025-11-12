<script setup lang="ts">
import axios from 'axios';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';

const { t } = useI18n();
const date = useDate();

const emit = defineEmits(['form-saved']);

const props = defineProps(['solicitud']);

const today = new Date();
const fechaPublicacion = computed(() => {
    if (props.solicitud.modelo.configuracion && props.solicitud.modelo.configuracion.fecha_publicacion_resultados != null) {
        return new Date(props.solicitud.modelo.configuracion.fecha_publicacion_resultados);
    }
    return null;
});

async function aceptarSeleccion() {
    axios
        .get(route('ingreso-solicitud-seleccion-aceptar', { id: props.solicitud.id }))
        .then(function (response) {})
        .catch(function (error) {
            console.error('Error fetching data:', error);
        });
}
</script>
<template>
    <v-container v-if="fechaPublicacion && today > fechaPublicacion">
        <v-alert border="top" type="success" variant="outlined" prominent v-if="solicitud.seleccionado">
            <span class="text-h6">{{ solicitud.solicitante.persona.nombreCompleto }} </span>, {{ $t('aspirante._ha_sido_msj_') }}
            <span class="text-h5 text-uppercase font-weight-black">
                {{ solicitud.solicitante.persona.sexo.descripcion === 'Femenino' ? $t('aspirante._seleccionada_') : $t('aspirante._seleccionado_') }}
                <v-icon icon="mdi-check-decagram" size="x-large"></v-icon>
            </span>
            <v-row class="mt-2">
                <v-col cols="1"> {{ $t('sede._sede_') }}: </v-col>
                <v-col class="font-weight-bold text-decoration-underline"> {{ props.solicitud.sede.nombre }}</v-col>
            </v-row>
            <v-row>
                <v-col cols="1"> {{ $t('carrera._singular_') }}: </v-col>
                <v-col class="font-weight-bold text-decoration-underline">
                    ({{ props.solicitud.codigo_carrera }}) {{ props.solicitud.nombre_carrera }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="1"> {{ $t('_opcion_') }}: </v-col>
                <v-col class="font-weight-bold text-decoration-underline"> {{ props.solicitud.opcion }}</v-col>
            </v-row>
            <v-divider class="mb-8"></v-divider>
            {{ $t('aspirante._si_esta_de_acuerdo_msj_') }}
            <span class="font-weight-bold text-decoration-underline">{{ $t('aspirante._confirmar_ingreso_') }}</span>
            {{ $t('aspirante._a_la_universidad_') }}
            <v-divider class="mb-8"></v-divider>
            <v-btn prepend-icon="mdi-checkbox-marked-outline" variant="tonal" color="primary" @click="aceptarSeleccion">{{
                $t('aspirante._aceptar_ingreso_')
            }}</v-btn>
        </v-alert>
        <v-alert v-else border="top" type="warning" variant="outlined" prominent> No ha sido seleccionado </v-alert>
    </v-container>
    <v-container v-else>
        <v-alert border="top" type="info" variant="outlined" prominent>
            {{ $t('aspirante._resultados_disponibles_desde_') }}
            {{ date.format(props.solicitud.modelo.configuracion.fecha_publicacion_resultados, 'keyboardDateTime12h') }}
        </v-alert>
    </v-container>
</template>
