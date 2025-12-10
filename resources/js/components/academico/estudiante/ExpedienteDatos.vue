<script setup lang="ts">
import { CarreraSede, CarreraUnidadAcademica, Estudiante, Expediente } from '@/types/tipos';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const tab = ref(null);

const props = defineProps({
    expediente: {
        type: Array as PropType<Expediente[]>,
        required: true,
        default: () => [],
    },
    estudiante: {
        type: Object as PropType<Estudiante>,
        required: true,
    },
    carreraSede: {
        type: Object as PropType<CarreraSede>,
        required: true,
    },
    mallaCurricular: {
        type: Array as PropType<CarreraUnidadAcademica[]>,
        required: true,
        default: () => [],
    },
});

//computed expdiente aprobadas
const expedienteAprobadas = computed(() => {
    return props.expediente.filter((item) => item.estado.codigo === 'AP');
});

// expediente en curso
const expedienteEnCurso = computed(() => {
    return props.expediente.filter((item) => item.estado.codigo === 'EC');
});

const seleccionado = ref(null);
</script>

<template>
    <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4" stacked>
        <v-tab :value="1">
            <v-icon icon="mdi-folder-table-outline"></v-icon>
            <span v-if="!$vuetify.display.mobile">{{ $t('expediente._singular_') }}</span>
        </v-tab>
        <v-tab :value="2">
            <v-icon icon="mdi-table"></v-icon>
            <span v-if="!$vuetify.display.mobile">{{ $t('mallaCurricular._singular_') }}</span>
        </v-tab>
    </v-tabs>

    <v-tabs-window v-model="tab">
        <v-tabs-window-item :value="1">
            <v-container fluid>
                <span v-if="$vuetify.display.mobile" class="text-subtitle-1">{{ $t('expediente._singular_') }}</span>
                <v-table striped="even">
                    <thead>
                        <tr>
                            <th class="text-left">Semestre</th>
                            <th class="text-left">Asignatura</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Matricula</th>
                            <th class="text-center">Calificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in props.expediente" :key="item.id">
                            <td>{{ item.semestre.nombre }}</td>
                            <td>{{ item.carrera_unidad_academica.unidad_academica.nombre }}</td>
                            <td class="text-center">{{ item.estado.codigo }}</td>
                            <td class="text-center">{{ item.matricula }}</td>
                            <td class="text-center">{{ item.calificacion }}</td>
                        </tr>
                    </tbody>
                </v-table>
            </v-container>
        </v-tabs-window-item>
        <v-tabs-window-item :value="2">
            <v-container fluid>
                <span v-if="$vuetify.display.mobile" class="text-subtitle-1">{{ $t('mallaCurricular._singular_') }}</span>
                <v-table striped="even">
                    <thead>
                        <tr>
                            <th class="text-center">{{ $t('semestre._singular_') }}</th>
                            <th class="text-left">{{ $t('unidadAcademica._singular_') }}</th>
                            <th class="text-center">{{ $t('expediente._estado_') }}</th>
                            <th class="text-left">{{ $t('mallaCurricular._requisitos_') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in mallaCurricular" :key="item.id">
                            <td class="text-center">{{ item.semestre }}</td>
                            <td class="text-left">
                                <v-chip
                                    :variant="item.unidad_academica.id == seleccionado ? 'tonal' : 'text'"
                                    @click="seleccionado = item.unidad_academica.id"
                                    >{{ item.unidad_academica.nombre }}</v-chip
                                >
                            </td>
                            <td class="text-center">
                                <v-chip
                                    variant="outlined"
                                    v-if="
                                        expedienteAprobadas.some(
                                            (expediente) => expediente.carrera_unidad_academica.unidad_academica_id === item.unidad_academica_id,
                                        ) ||
                                        expedienteEnCurso.some(
                                            (expediente) => expediente.carrera_unidad_academica.unidad_academica_id === item.unidad_academica_id,
                                        )
                                    "
                                    :color="
                                        expedienteAprobadas.some(
                                            (expediente) => expediente.carrera_unidad_academica.unidad_academica_id === item.unidad_academica_id,
                                        )
                                            ? 'green'
                                            : 'primary'
                                    "
                                >
                                    {{
                                        expedienteAprobadas.some(
                                            (expediente) => expediente.carrera_unidad_academica.unidad_academica_id === item.unidad_academica_id,
                                        )
                                            ? 'AP'
                                            : 'EC'
                                    }}
                                </v-chip>
                            </td>
                            <td class="text-left">
                                <v-chip
                                    v-for="requisito in item.requisitos"
                                    :key="requisito.id"
                                    :color="
                                        expedienteAprobadas.some(
                                            (expediente) => expediente.carrera_unidad_academica.unidad_academica_id === requisito.unidad_academica_id,
                                        )
                                            ? 'green'
                                            : expedienteEnCurso.some(
                                                    (expediente) =>
                                                        expediente.carrera_unidad_academica.unidad_academica_id === requisito.unidad_academica_id,
                                                )
                                              ? 'primary'
                                              : ''
                                    "
                                    size="small"
                                    :variant="requisito.unidad_academica.id == seleccionado ? 'elevated' : 'outlined'"
                                    @click="seleccionado = requisito.unidad_academica.id"
                                >
                                    {{ requisito.unidad_academica.nombre + ' ' }}
                                </v-chip>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-container>
        </v-tabs-window-item>
    </v-tabs-window>
</template>
