<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps(['expediente']);

const localExpediente = computed(() => props.expediente.filter((item) => item.estado.codigo == 'EC'));
</script>
<template>
    <v-card class="mx-auto" rounded="xl">
        <v-card-title>{{ $t('inscripcion._asignaturas_en_curso_') }}</v-card-title>
        <v-card-text>
            <v-data-iterator :items="localExpediente" item-value="id">
                <template v-slot:default="{ items, isExpanded, toggleExpand }">
                    <v-row>
                        <v-col v-for="item in items" :key="item.id" cols="12" md="6" sm="12">
                            <v-card variant="outlined">
                                <v-card-title class="d-flex align-center text-subtitle-1">
                                    <h4>📚 {{ item.raw.carrera_unidad_academica.unidad_academica.nombre }}</h4>
                                </v-card-title>
                                <v-card-subtitle>
                                    {{ $t('_codigo_') }} {{ item.raw.carrera_unidad_academica.unidad_academica.codigo }}
                                </v-card-subtitle>
                                <v-card-subtitle class="text-high-emphasis mb-1 opacity-100">
                                    {{ $t('inscripcion._matricula_') + ' ' + item.raw.matricula }}
                                </v-card-subtitle>
                                <v-card-subtitle class="text-high-emphasis mb-1 opacity-100">
                                    <span class="text-green-darken-2">
                                        {{ $t('inscripcion._calificacion_') + ' ' + +(item.raw.calificacion ? item.raw.calificacion : '0') }}
                                    </span>
                                </v-card-subtitle>

                                <div class="px-4">
                                    <v-switch
                                        :label="$t('inscripcion._ver_evaluaciones_')"
                                        :model-value="isExpanded(item)"
                                        @click="() => toggleExpand(item)"
                                        color="green-darken-2"
                                        base-color="primary"
                                    ></v-switch>
                                </div>

                                <v-divider></v-divider>

                                <v-expand-transition>
                                    <div v-if="isExpanded(item)">
                                        <v-list :lines="false" density="compact">
                                            <v-list-item :title="`🔥 Calories: `" active></v-list-item>
                                            <v-list-item :title="`🍔 Fat: `"></v-list-item>
                                            <v-list-item :title="`🍞 Carbs: `"></v-list-item>
                                            <v-list-item :title="`🍗 Protein: `"></v-list-item>
                                            <v-list-item :title="`🧂 Sodium: `"></v-list-item>
                                            <v-list-item :title="`🦴 Calcium: `"></v-list-item>
                                            <v-list-item :title="`📚 Iron: `"></v-list-item>
                                        </v-list>
                                    </div>
                                </v-expand-transition>
                            </v-card>
                        </v-col>
                    </v-row>
                </template>
            </v-data-iterator>
        </v-card-text>
    </v-card>
</template>
