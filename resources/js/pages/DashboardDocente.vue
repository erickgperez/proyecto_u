<script setup lang="ts">
import CargaAcademica from '@/components/academico/docente/CargaAcademica.vue';
import { User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const page = usePage();
const persona = page.props.auth.persona;
const user = page.props.auth.user as User;
const docente = ref(null);

onMounted(() => {
    axios
        .get(route('academico-persona-docente-data', { uuid: persona.uuid }))
        .then(function (response) {
            docente.value = response.data.docente;
        })
        .catch(function (error) {
            console.error('Error fetching data:', error);
        });
});
</script>

<template>
    <v-row>
        <v-col cols="12">
            <v-card>
                <v-card-title class="text-capitalize text-h6"
                    >{{ persona.sexo?.descripcion === 'Femenino' ? $t('_bienvenida_') : $t('_bienvenido_') }},
                    {{ persona.nombreCompleto }}</v-card-title
                >
                <v-card-subtitle v-if="docente">
                    <v-row>
                        <v-col cols="12" md="4" class="d-flex align-center"> {{ $t('docente._codigo_') }}: {{ docente.codigo }} </v-col>
                    </v-row>
                </v-card-subtitle>
            </v-card>
        </v-col>
        <CargaAcademica :cargaTitular="docente?.carga_titular" :cargaAsociado="docente?.imparte" :uuidDocente="docente?.uuid" />
    </v-row>
</template>
<style scoped>
.on-hover-alert {
    background-color: #7195ad15 !important;
    transition: background-color 0.3s ease;
}
</style>
