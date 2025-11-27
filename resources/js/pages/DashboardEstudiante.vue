<script setup lang="ts">
import { User } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';

const page = usePage();
const persona = page.props.auth.persona;
const user = page.props.auth.user as User;
const estudiante = ref(null);

onMounted(() => {
    axios
        .get(route('academico-persona-estudiante-data', { uuid: persona.uuid }))
        .then(function (response) {
            estudiante.value = response.data.estudiante;
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
                <v-card-title class="text-capitalize text-h6">{{ persona.sexo.descripcion ==='Femenino' ? 'Bienvenida' : 'Bienvenido' }}, {{ persona.nombreCompleto }}</v-card-title>
                <v-card-subtitle v-if="estudiante">
                    <v-row v-if="estudiante.carrera_sede.length > 0">
                        <v-col cols="12" md="6">
                            {{ $t('carrera._singular_') }}: {{ estudiante.carrera_sede[0].carrera.nombreCompleto }}
                        </v-col>
                        <v-col cols="12" md="6" class="text-right">
                            {{ $t('sede._sede_') }}: {{ estudiante.carrera_sede[0].sede.nombre }}
                        </v-col>
                    </v-row>
                </v-card-subtitle>
            </v-card>
        </v-col>
        <v-col cols="12" md="3">
            <Link :href="route('dashboard')">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-note-plus-outline"
                        color="indigo-darken-2"
                        :title="$t('dashboard._inscripcion_')"
                        :text="$t('dashboard._asignaturas_')"
                    >
                    </v-alert>
                </v-hover>
            </Link>
        </v-col>
        <v-col cols="12" md="3">
            <Link :href="route('dashboard')">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-note-plus-outline"
                        color="brown-darken-2"
                        :title="$t('dashboard._expediente_')"
                        :text="$t('dashboard._academico_')"
                    >
                    </v-alert>
                </v-hover>
            </Link>
        </v-col>
        <v-col cols="12" md="3">
            <Link :href="route('dashboard')">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-card-account-details-outline"
                        color="orange-darken-4"
                        :title="$t('dashboard._perfil_')"
                    >
                    </v-alert>
                </v-hover>
            </Link>
        </v-col>
        <v-col cols="12" md="3">
            <Link :href="route('dashboard')">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        class="ma-0"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-file-document-outline"
                        color="deep-purple-darken-1"
                        :title="$t('dashboard._documentos_')"
                    >
                    </v-alert>
                </v-hover>
            </Link>
        </v-col>
    </v-row>
</template>
<style scoped>
.on-hover-alert {
    background-color: #7195ad15 !important;
    transition: background-color 0.3s ease;
}
</style>
