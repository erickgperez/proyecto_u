<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useDate } from 'vuetify';

const date = useDate();

const props = defineProps(['solicitud']);

const today = new Date();
const fechaPublicacion = computed(() => {
    if (props.solicitud.modelo.configuracion && props.solicitud.modelo.configuracion.fecha_publicacion_resultados != null) {
        return new Date(props.solicitud.modelo.configuracion.fecha_publicacion_resultados);
    }
    return null;
});
</script>
<template>
    <v-container v-if="fechaPublicacion && today > fechaPublicacion">
        <v-alert border="top" color="success" variant="outlined" prominent v-if="solicitud.seleccionado" >
            <span class="text-h6">{{ solicitud.solicitante.persona.nombreCompleto }} </span>, {{ $t('aspirante._ha_sido_msj_') }}
            <span class="text-h6 text-uppercase font-weight-black">
                {{ solicitud.solicitante.persona.sexo.descripcion === 'Femenino' ? $t('aspirante._seleccionada_') : $t('aspirante._seleccionado_') }}
                <v-icon icon="mdi-check-decagram" size="x-large"></v-icon>
            </span>
            <v-row class="">
                <v-col cols="12" md="6"> {{ $t('sede._sede_') }}: </v-col>
                <v-col cols="12" md="6" class="font-weight-bold text-decoration-underline"> {{ props.solicitud.sede.nombre }}</v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="6"> {{ $t('carrera._singular_') }}: </v-col>
                <v-col cols="12" md="6" class="font-weight-bold text-decoration-underline">
                    ({{ props.solicitud.codigo_carrera }}) {{ props.solicitud.nombre_carrera }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="6"> {{ $t('_opcion_') }}: </v-col>
                <v-col cols="12" md="6" class="font-weight-bold text-decoration-underline"> {{ props.solicitud.opcion }}</v-col>
            </v-row>
            <v-divider class="mb-8"></v-divider>
            {{ $t('aspirante._si_esta_de_acuerdo_msj_') }}
            <span class="font-weight-bold text-decoration-underline">{{ $t('aspirante._confirmar_ingreso_') }}</span>
            {{ $t('aspirante._a_la_universidad_') }}
            <v-divider class="mb-8"></v-divider>
            <Link :href="route('ingreso-solicitud-seleccion-aceptar', { id: props.solicitud.id })">
                <v-btn prepend-icon="mdi-checkbox-marked-outline" variant="tonal" color="primary">
                    {{ $t('aspirante._aceptar_ingreso_') }}
                </v-btn>
            </Link>
        </v-alert>
        <v-alert v-else border="top" type="warning" variant="outlined" prominent> {{ $t('aspirante._no_seleccionado_msj_') }} </v-alert>
    </v-container>
    <v-container v-else>
        <v-alert border="top" type="info" variant="outlined" prominent>
            {{ $t('aspirante._resultados_disponibles_desde_') }}
            {{ date.format(props.solicitud.modelo.configuracion.fecha_publicacion_resultados, 'keyboardDateTime12h') }}
        </v-alert>
    </v-container>
</template>
