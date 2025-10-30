<script setup lang="ts">
import { CarreraSede, InfoSede, Sede } from '@/types/tipos';
import { PropType } from 'vue';

defineProps({
    infoSede: {
        type: Array as PropType<InfoSede[]>,
        required: true,
        default: () => [],
    },
    sede: Object as PropType<Sede>,
    ultimaSeleccion: Object as PropType<CarreraSede>,
    carrerasSede: Array as PropType<CarreraSede[]>,
});
</script>
<template>
    <v-navigation-drawer location="right" permanent class="rounded-r-xl" width="360">
        <v-list density="compact" nav>
            <div class="text-center" v-for="sede_ in infoSede" :key="sede_.nombre">
                <v-card :title="$t('convocatoria._consolidado_sede_')" class="elevation-4 mb-1 border-2" v-if="sede_.seleccionados > 0">
                    <v-card-text>
                        <span class="text-caption text-pink-darken-4" v-if="Object.keys(infoSede).length > 1">SEDE: {{ sede_.nombre }}</span>
                        <v-container class="text-center">
                            <v-progress-linear
                                :model-value="(sede_.seleccionados / sede_.cupo) * 100"
                                height="30"
                                color="indigo-lighten-2"
                                class="border-md elevation-6 mb-2"
                                rounded
                            >
                                <strong> {{ $t('convocatoria._seleccionados_') }} {{ sede_.seleccionados }}/{{ sede_.cupo }} </strong>
                            </v-progress-linear>
                            <v-pie
                                v-if="sede_.seleccionados > 0"
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
                                        value: sede_.seleccionados > 0 ? Math.round((sede_.seleccionados_publico / sede_.seleccionados) * 100) : 0,
                                    },
                                    {
                                        key: 2,
                                        title: $t('convocatoria._privado_'),
                                        value: sede_.seleccionados > 0 ? Math.round((sede_.seleccionados_privado / sede_.seleccionados) * 100) : 0,
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
            </div>
            <v-divider></v-divider>
            <!--<v-card v-if="ultimaSeleccion != null" :title="$t('aspirante._ultima_seleccion_')" class="elevation-4 ma-2 border-1">
                <v-card-text>
                    <span class="text-caption text-orange-darken-4">{{ ultimaSeleccion.carrera }}</span>
                    <v-divider></v-divider>
                    <span class="text-caption text-pink-darken-4" v-if="sede">SEDE: {{ ultimaSeleccion.sede }}</span>
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
            <v-divider></v-divider>-->
            <div v-for="cs in carrerasSede" :key="cs.id">
                <v-card class="elevation-4 border-1" v-if="cs.seleccionados > 0">
                    <v-card-text>
                        <span class="text-caption text-green-darken-3">{{ cs.carrera }}</span>
                        <v-divider></v-divider>
                        <span class="text-caption text-pink-darken-4" v-if="Object.keys(infoSede).length > 1">SEDE: {{ cs.sede }}</span>
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
            </div>
        </v-list>
    </v-navigation-drawer>
</template>
