<script setup lang="ts">
import { CarreraSede, InfoSede, Sede } from '@/types/tipos';
import { PropType } from 'vue';

defineProps({
    infoSede: {
        type: Object as PropType<InfoSede>,
        required: true,
        default: () => ({ cupoSede: 0, seleccionadosSede: 0, seleccionadosPublicoSede: 0, seleccionadosPrivadoSede: 0 }),
    },
    sede: Object as PropType<Sede>,
    ultimaSeleccion: Object as PropType<CarreraSede>,
    carrerasSede: Array as PropType<CarreraSede[]>,
});
</script>
<template>
    <v-navigation-drawer location="right" permanent class="rounded-r-xl" width="350">
        <v-list density="compact" nav>
            <v-card :title="$t('convocatoria._consolidado_sede_')" class="elevation-4 ma-2 border-1">
                <v-card-text>
                    <span class="text-caption text-pink-darken-4" v-if="sede">SEDE: {{ sede.nombre }}</span>
                    <v-container class="text-center">
                        <v-progress-linear
                            :model-value="(infoSede.seleccionadosSede / infoSede.cupoSede) * 100"
                            height="30"
                            color="indigo-lighten-2"
                            class="border-md elevation-6 mb-2"
                            rounded
                        >
                            <strong> {{ $t('convocatoria._seleccionados_') }} {{ infoSede.seleccionadosSede }}/{{ infoSede.cupoSede }} </strong>
                        </v-progress-linear>
                        <v-pie
                            v-if="infoSede.seleccionadosSede > 0"
                            :gauge-cut="100"
                            hide-slice
                            :inner-cut="70"
                            animation
                            :palette="['#00876c', '#d43d51']"
                            :size="100"
                            reveal
                            :legend="{ position: 'right', textFormat: '[title] ([value]%)' }"
                            :items="[
                                {
                                    key: 1,
                                    title: $t('convocatoria._publico_'),
                                    value:
                                        infoSede.seleccionadosSede > 0
                                            ? Math.round((infoSede.seleccionadosPublicoSede / infoSede.seleccionadosSede) * 100)
                                            : 0,
                                },
                                {
                                    key: 2,
                                    title: $t('convocatoria._privado_'),
                                    value:
                                        infoSede.seleccionadosSede > 0
                                            ? Math.round((infoSede.seleccionadosPrivadoSede / infoSede.seleccionadosSede) * 100)
                                            : 0,
                                },
                            ]"
                        >
                            <template #legend="{ items }">
                                <div class="d-flex flex-column">
                                    <div v-for="(item, i) in items" :key="i" class="d-flex align-center">
                                        <div :style="{ background: item.color, width: '12px', height: '12px' }" class="mr-2 rounded" />
                                        {{ item.title }} ({{ item.value }}%)
                                    </div>
                                </div>
                            </template>
                        </v-pie>
                    </v-container>
                </v-card-text>
            </v-card>
            <v-divider></v-divider>
            <v-card v-if="ultimaSeleccion != null" :title="$t('aspirante._ultima_seleccion_')" class="elevation-4 ma-2 border-1">
                <v-card-text>
                    <span class="text-caption text-orange-darken-4">{{ ultimaSeleccion.carrera }}</span>
                    <v-container class="text-center">
                        <v-progress-linear
                            :model-value="(ultimaSeleccion.seleccionados / ultimaSeleccion.cupo) * 100"
                            height="30"
                            color="indigo-lighten-2"
                            class="border-md elevation-6 mb-2"
                            rounded
                        >
                            <strong>{{ $t('convocatoria._seleccionados_') }} {{ ultimaSeleccion.seleccionados }}/{{ ultimaSeleccion.cupo }} </strong>
                        </v-progress-linear>
                        <v-pie
                            v-if="ultimaSeleccion.seleccionados > 0"
                            :gauge-cut="100"
                            hide-slice
                            :inner-cut="70"
                            animation
                            :palette="['#00876c', '#d43d51']"
                            :size="100"
                            reveal
                            :legend="{ position: 'right', textFormat: '[title] ([value]%)' }"
                            :items="[
                                {
                                    key: 1,
                                    title: $t('convocatoria._publico_'),
                                    value:
                                        ultimaSeleccion.seleccionados > 0
                                            ? Math.round((ultimaSeleccion.seleccionados_publico / ultimaSeleccion.seleccionados) * 100)
                                            : 0,
                                },
                                {
                                    key: 2,
                                    title: $t('convocatoria._privado_'),
                                    value:
                                        ultimaSeleccion.seleccionados > 0
                                            ? Math.round((ultimaSeleccion.seleccionados_privado / ultimaSeleccion.seleccionados) * 100)
                                            : 0,
                                },
                            ]"
                        >
                            <template #legend="{ items }">
                                <div class="d-flex flex-column">
                                    <div v-for="(item, i) in items" :key="i" class="d-flex align-center">
                                        <div :style="{ background: item.color, width: '12px', height: '12px' }" class="mr-2 rounded" />
                                        {{ item.title }} ({{ item.value }}%)
                                    </div>
                                </div>
                            </template>
                        </v-pie>
                    </v-container>
                </v-card-text>
            </v-card>
            <v-divider></v-divider>
            <v-card v-for="cs in carrerasSede" :key="cs.id" class="elevation-4 ma-2 border-1">
                <v-card-text>
                    <span class="text-caption text-green-darken-3">{{ cs.carrera }}</span>
                    <v-container class="text-center">
                        <v-progress-linear
                            rounded
                            :model-value="(cs.seleccionados / cs.cupo) * 100"
                            color="indigo-lighten-2"
                            height="30"
                            class="border-md elevation-6 mb-2"
                        >
                            <strong>{{ $t('convocatoria._seleccionados_') }} {{ cs.seleccionados }}/{{ cs.cupo }} </strong>
                        </v-progress-linear>
                        <v-pie
                            v-if="cs.seleccionados > 0"
                            :gauge-cut="100"
                            hide-slice
                            :inner-cut="70"
                            animation
                            :palette="['#00876c', '#d43d51']"
                            :size="100"
                            reveal
                            :legend="{ position: 'right', textFormat: '[title] ([value]%)' }"
                            :items="[
                                {
                                    key: 1,
                                    title: $t('convocatoria._publico_'),
                                    value: cs.seleccionados > 0 ? Math.round((cs.seleccionados_publico / cs.seleccionados) * 100) : 0,
                                },
                                {
                                    key: 2,
                                    title: $t('convocatoria._privado_'),
                                    value: cs.seleccionados > 0 ? Math.round((cs.seleccionados_privado / cs.seleccionados) * 100) : 0,
                                },
                            ]"
                        >
                            <template #legend="{ items }">
                                <div class="d-flex flex-column">
                                    <div v-for="(item, i) in items" :key="i" class="d-flex align-center">
                                        <div :style="{ background: item.color, width: '12px', height: '12px' }" class="mr-2 rounded" />
                                        {{ item.title }} ({{ item.value }}%)
                                    </div>
                                </div>
                            </template>
                        </v-pie>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-list>
    </v-navigation-drawer>
</template>
