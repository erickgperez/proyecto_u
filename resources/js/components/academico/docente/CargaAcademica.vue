<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
const props = defineProps(['cargaTitular', 'cargaAsociado', 'uuidDocente']);
</script>
<template>
    <v-card class="mx-auto" rounded="xl" width="98%">
        <v-card-title>{{ $t('docente._carga_academica_') }}</v-card-title>
        <v-card-text>
            <v-data-iterator :items="cargaTitular" item-value="id">
                <template v-slot:default="{ items, isExpanded, toggleExpand }">
                    <v-row>
                        <v-col v-for="item in items" :key="item.id" cols="12" md="6">
                            <v-card variant="tonal" color="indigo-darken-3" v-if="item.raw.semestre" rounded="xl" hover>
                                <v-card-title class="d-flex align-center text-subtitle-1">
                                    <h4>📚 {{ item.raw.carrera_unidad_academica.unidad_academica.nombre }}</h4>
                                </v-card-title>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('_codigo_') }}:</span>
                                    {{ item.raw.carrera_unidad_academica.unidad_academica.codigo }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('carrera._singular_') }}:</span>
                                    {{ item.raw.carrera_unidad_academica.carrera.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('semestre._singular_') }}:</span> {{ item.raw.semestre.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('docente._tipo_asignacion_') }}:</span>
                                    <span class="font-weight-bold text-decoration-underline text-h6 text-primary">{{
                                        $t('semestre._docente_titular_')
                                    }}</span>
                                </v-card-subtitle>
                                <v-card-actions>
                                    <Link :href="route('academico-evaluacion-index', { uuid: item.raw.uuid })">
                                        <v-btn :text="$t('docente._configurar_evaluaciones_')" variant="text"></v-btn>
                                    </Link>
                                    <v-spacer></v-spacer>
                                    <Link :href="route('reportes-asignatura-inscritos-titular', { uuid: item.raw.uuid })">
                                        <v-btn icon="mdi-chart-box-multiple-outline"></v-btn>
                                    </Link>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </template>
            </v-data-iterator>
            <v-divider class="my-4"></v-divider>
            <v-data-iterator :items="cargaAsociado" item-value="id">
                <template v-slot:default="{ items, isExpanded, toggleExpand }">
                    <v-row>
                        <v-col v-for="item in items" :key="item.id" cols="12" md="6">
                            <v-card variant="tonal" color="indigo-darken-3" v-if="item.raw.oferta.semestre" rounded="xl" hover>
                                <v-card-title class="d-flex align-center text-subtitle-1">
                                    <h4>📚 {{ item.raw.oferta.carrera_unidad_academica.unidad_academica.nombre }}</h4>
                                </v-card-title>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('_codigo_') }}:</span>
                                    {{ item.raw.oferta.carrera_unidad_academica.unidad_academica.codigo }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('sede._sede_') }}:</span> {{ item.raw.carrera_sede.sede.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('carrera._singular_') }}:</span>
                                    {{ item.raw.oferta.carrera_unidad_academica.carrera.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('semestre._singular_') }}:</span> {{ item.raw.oferta.semestre.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('docente._tipo_asignacion_') }}:</span>
                                    <span class="font-weight-bold text-decoration-underline text-h6 text-secondary">{{
                                        $t('docente._docente_asociado_')
                                    }}</span>
                                </v-card-subtitle>
                                <v-card-actions>
                                    <Link
                                        :href="route('academico-evaluacion-registro_notas', { uuid: item.raw.uuid, uuidDocente: props.uuidDocente })"
                                    >
                                        <v-btn :text="$t('docente._ingresar_notas_')" variant="text"></v-btn>
                                    </Link>
                                    <v-spacer></v-spacer>
                                    <Link :href="route('reportes-asignatura-inscritos-asociado', { uuid: item.raw.uuid })">
                                        <v-btn icon="mdi-chart-box-multiple-outline"></v-btn>
                                    </Link>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </template>
            </v-data-iterator>
        </v-card-text>
    </v-card>
</template>
