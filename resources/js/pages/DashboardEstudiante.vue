<script setup lang="ts">
import AsignaturasEnCurso from '@/components/academico/estudiante/AsignaturasEnCurso.vue';
import { User } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const page = usePage();
const persona = page.props.auth.persona;
const user = page.props.auth.user as User;
const estudiante = ref(null);
const estudianteCarreraSede = ref(null);
const carrerasSede = ref([]);

onMounted(() => {
    axios
        .get(route('academico-persona-estudiante-data', { uuid: persona.uuid }))
        .then(function (response) {
            estudiante.value = response.data.estudiante;
            estudianteCarreraSede.value = response.data.estudiante.carrera_sede[0];
            carrerasSede.value = response.data.estudiante.carrera_sede;
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
                    >{{ persona.sexo.descripcion === 'Femenino' ? 'Bienvenida' : 'Bienvenido' }},{{ persona.nombreCompleto }}</v-card-title
                >
                <v-card-subtitle v-if="estudiante">
                    <v-row>
                        <v-col cols="12" md="4" class="d-flex align-center"> {{ $t('estudiante._carnet_') }}: {{ estudiante.carnet }} </v-col>
                        <v-col cols="12" md="8" v-if="estudianteCarreraSede">
                            <v-select
                                class="mt-2"
                                v-model="estudianteCarreraSede"
                                :items="carrerasSede"
                                item-title="titulo2"
                                item-value="id"
                                :label="$t('estudiante._carrera_sede_')"
                                variant="plain"
                                density="compact"
                                single-line
                                return-object
                            ></v-select>
                            <!--{{ $t('carrera._singular_') }}: {{ estudianteCarreraSede.carrera.nombreCompleto }} :: {{ estudianteCarreraSede.sede.nombre }} -->
                        </v-col>
                    </v-row>
                </v-card-subtitle>
            </v-card>
        </v-col>
        <v-col cols="12" md="3">
            <Link :href="route('academico-estudiante-inscripcion-carrera-sede', { uuid: estudiante?.uuid ?? 0, id: estudianteCarreraSede?.id ?? 0 })">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        :class="{ 'on-hover-alert': isHovering }"
                        class="bg-white"
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
            <Link :href="route('academico-estudiante-expediente-carrera-sede', { uuid: estudiante?.uuid ?? 0, id: estudianteCarreraSede?.id ?? 0 })">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        :class="{ 'on-hover-alert': isHovering }"
                        class="bg-white"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-folder-table-outline"
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
                        class="bg-white"
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
                        :class="{ 'on-hover-alert': isHovering }"
                        class="bg-white"
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
    <v-divider class="my-4"></v-divider>
    <AsignaturasEnCurso :expediente="estudiante?.expediente" v-if="estudiante && estudiante.expediente.length > 0" />
</template>
<style scoped>
.on-hover-alert {
    background-color: #7195ad15 !important;
    transition: background-color 0.3s ease;
}
</style>
