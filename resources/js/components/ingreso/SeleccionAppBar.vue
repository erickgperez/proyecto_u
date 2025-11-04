<script setup lang="ts">
defineProps(['convocatoria', 'sede', 'step']);

const emit = defineEmits(['changeStep', 'changeDrawer']);
</script>
<template>
    <v-app-bar :elevation="0" class="bg-blue-grey-lighten-3">
        <v-app-bar-title>
            <v-card class="mx-auto bg-transparent">
                <template v-slot:title>
                    <span>{{ convocatoria?.descripcion }}</span>
                </template>
                <template v-slot:subtitle v-if="sede">
                    <span>SEDE: {{ sede?.nombre }}</span>
                    <span class="ms-3" v-if="convocatoria && convocatoria.configuracion && convocatoria.configuracion.cuota_sector_publico">
                        ({{ $t('convocatoria._cuota_cupo_sector_') }}:
                        <span class="text-pink font-weight-black">
                            {{ $t('convocatoria._publico_') }} - {{ convocatoria.configuracion.cuota_sector_publico }}%
                        </span>
                        ::
                        <span class="text-pink font-weight-black">
                            {{ $t('convocatoria._privado_') }} - {{ 100 - convocatoria.configuracion.cuota_sector_publico }}%
                        </span>
                        )
                    </span>
                </template>
            </v-card>
        </v-app-bar-title>

        <template v-slot:append>
            <v-btn v-if="step == 1" icon="mdi-application-cog" @click.stop="emit('changeDrawer')" :title="$t('_parametros_')"></v-btn>
            <v-btn v-if="step == 1" icon="mdi-chart-bar" @click.stop="emit('changeStep', 2)" :title="$t('_grafico_dinamico_')"></v-btn>

            <v-btn v-if="step == 2" icon="mdi-format-list-checks" @click.stop="emit('changeStep', 1)" :title="$t('_listado_seleccion_')"></v-btn>
        </template>
    </v-app-bar>
</template>
