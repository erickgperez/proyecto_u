<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps(['cargaTitular', 'cargaAsociado']);

</script>
<template>
    <v-card class="mx-auto" rounded="xl" width="98%">
        <v-card-title>{{ $t('docente._carga_academica_') }}</v-card-title>
        <v-card-text>
            <v-data-iterator :items="cargaTitular" item-value="id">
                <template v-slot:default="{ items, isExpanded, toggleExpand }">
                    <v-row>
                        <v-col v-for="item in items" :key="item.id" cols="12" md="6" >
                            <v-card variant="outlined">
                                <v-card-title class="d-flex align-center text-subtitle-1">
                                    <h4>📚 {{ item.raw.carrera_unidad_academica.unidad_academica.nombre }}</h4>
                                </v-card-title>                                
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('_codigo_') }}:</span> {{ item.raw.carrera_unidad_academica.unidad_academica.codigo }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('carrera._singular_') }}:</span> {{ item.raw.carrera_unidad_academica.carrera.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('semestre._singular_') }}:</span> {{ item.raw.semestre.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('docente._tipo_asignacion_') }}:</span> <span class="text-primary font-weight-bold text-decoration-underline text-h6">{{ $t('semestre._docente_titular_') }}</span>
                                </v-card-subtitle>
                                <v-card-actions>
                                    <v-btn
                                        color="deep-purple-accent-4"
                                        :text="$t('docente._configurar_evaluaciones_')"
                                        variant="text"
                                    ></v-btn>
                                </v-card-actions>

                                <!--<div class="px-4">
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
                                            <v-list-item title="Eval 1"></v-list-item>
                                            <v-list-item title="Eval 2"></v-list-item>
                                            <v-list-item title="Eval 3"></v-list-item>
                                            <v-list-item title="Eval 4"></v-list-item>
                                            <v-list-item title="Eval 5"></v-list-item>
                                        </v-list>
                                    </div>
                                </v-expand-transition>
                                -->
                            </v-card>
                        </v-col>
                    </v-row>
                </template>
            </v-data-iterator>
            <v-divider class="my-4"></v-divider>
            <v-data-iterator :items="cargaAsociado" item-value="id">
                <template v-slot:default="{ items, isExpanded, toggleExpand }">
                    <v-row>
                        <v-col v-for="item in items" :key="item.id" cols="12" md="6" >
                            <v-card variant="outlined">
                                <v-card-title class="d-flex align-center text-subtitle-1">
                                    <h4>📚 {{ item.raw.oferta.carrera_unidad_academica.unidad_academica.nombre }}</h4>
                                </v-card-title>                                
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('_codigo_') }}:</span> {{ item.raw.oferta.carrera_unidad_academica.unidad_academica.codigo }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('sede._sede_') }}:</span> {{ item.raw.carrera_sede.sede.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('carrera._singular_') }}:</span> {{ item.raw.oferta.carrera_unidad_academica.carrera.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('semestre._singular_') }}:</span> {{ item.raw.oferta.semestre.nombre }}
                                </v-card-subtitle>
                                <v-card-subtitle>
                                    <span class="font-weight-bold">{{ $t('docente._tipo_asignacion_') }}:</span> <span class="text-secondary font-weight-bold text-decoration-underline text-h6">{{ $t('docente._docente_asociado_') }}</span>
                                </v-card-subtitle>
                                <v-card-actions>
                                    <v-btn
                                        color="primary"
                                        :text="$t('docente._ingresar_notas_')"
                                        variant="text"
                                    ></v-btn>
                                </v-card-actions>

                                <!--<div class="px-4">
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
                                            <v-list-item title="Eval 1"></v-list-item>
                                            <v-list-item title="Eval 2"></v-list-item>
                                            <v-list-item title="Eval 3"></v-list-item>
                                            <v-list-item title="Eval 4"></v-list-item>
                                            <v-list-item title="Eval 5"></v-list-item>
                                        </v-list>
                                    </div>
                                </v-expand-transition>
                                -->
                            </v-card>
                        </v-col>
                    </v-row>
                </template>
            </v-data-iterator>
        </v-card-text>
    </v-card>
</template>
