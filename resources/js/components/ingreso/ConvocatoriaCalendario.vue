<script setup lang="ts">
import Calendario from '../Calendario.vue';

const items = [
    {
        step: 'Paso 1',
        name: 'Nombre actividad 1',
        start: '2025-09-01',
        end: '2025-09-13',
        icon: 'mdi-numeric-1',
    },
    {
        step: 'Paso 2',
        name: 'Nombre actividad 2',
        start: '2025-09-15',
        end: '2025-09-30',
        icon: 'mdi-numeric-2',
    },
    {
        step: 'Paso 3',
        name: 'Nombre actividad 3',
        start: '2025-10-01',
        end: '2025-10-16',
        icon: 'mdi-numeric-3',
    },
    {
        step: 'Paso 4',
        name: 'Nombre actividad 4',
        start: '2025-10-19',
        end: '2025-10-31',
        icon: 'mdi-numeric-4',
    },
];
</script>

<template>
    <Calendario :actividades="items"></Calendario>
</template>
