<script setup lang="ts">
const props = defineProps(['carrerasSeleccionadas']);
</script>
<template>
    <div v-if="props.carrerasSeleccionadas.length > 0">
        <v-icon color="success" size="x-large" icon="mdi-numeric-1-box"></v-icon> {{ $t('ingreso._primera_opcion_') }}:
        <span class="text-success text-high-emphasis font-weight-black">{{ props.carrerasSeleccionadas[0].title }}</span>
    </div>
    <div v-if="props.carrerasSeleccionadas.length > 1">
        <v-icon color="warning" size="x-large" icon="mdi-numeric-2-box"></v-icon> {{ $t('ingreso._segunda_opcion_') }}:
        <span class="text-warning text-high-emphasis font-weight-black">{{ props.carrerasSeleccionadas[1].title }}</span>
    </div>
    <div v-if="props.carrerasSeleccionadas.length > 2">
        <v-icon color="primary" size="x-large" icon="mdi-numeric-3-box"></v-icon> {{ $t('ingreso._tercera_opcion_') }}:
        <span class="text-high-emphasis font-weight-black text-primary"> {{ props.carrerasSeleccionadas[2].title }}</span>
    </div>
</template>
