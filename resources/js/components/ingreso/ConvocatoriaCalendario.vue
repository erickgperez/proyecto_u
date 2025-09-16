<script setup lang="ts">
import { useDate } from 'vuetify';

const date = useDate();

const getColor = (item: any, tipo: string): string => {
    let _color = '';
    const currentDate = new Date();

    // Eventos pasados
    if (date.isAfter(currentDate, date.date(item.end))) {
        _color = 'grey-lighten-1';
    } else if (date.isAfter(currentDate, date.date(item.start)) && date.isBefore(currentDate, date.date(item.end))) {
        //Eventos en curso
        _color = 'green-lighten-1';
    } else {
        _color = 'red-lighten-2';
    }
    return (tipo == '' ? '' : tipo + '-') + _color;
};

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
    <v-layout>
        <v-app-bar color="primary">
            <v-toolbar-title>{{ $t('convocatoria._listado_actividades_') }}</v-toolbar-title>

            <v-btn icon="mdi-calendar-plus" :title="$t('calendario._agregar_actividad_')"></v-btn>
        </v-app-bar>

        <v-main>
            <v-container>
                <v-timeline align="start">
                    <v-timeline-item v-for="(item, i) in items" :key="i" :dot-color="getColor(item, '')" fill-dot :icon="item.icon">
                        <template v-slot:opposite>
                            <div :class="getColor(item, 'text')" class="headline font-weight-bold pt-1">{{ item.step }} : {{ item.name }}</div>
                        </template>
                        <v-card>
                            <v-card-title class="text-h6" :class="getColor(item, 'bg')">
                                {{ $t('_desde_') + ' ' }} {{ date.format(item.start, 'keyboardDate') }} {{ $t('_hasta_') + ' ' }}
                                {{ date.format(item.end, 'keyboardDate') }}
                            </v-card-title>
                            <v-card-text class="text--primary bg-white">
                                <p>
                                    INDICACIONES: Lorem ipsum dolor sit amet, no nam oblique veritus. Commune scaevola imperdiet nec ut, sed euismod
                                    convenire principes at. Est et nobis iisque percipit, an vim zril disputando voluptatibus, vix an salutandi
                                    sententiae.
                                </p>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn icon="mdi-calendar-edit" :title="$t('calendario._editar_actividad_')"></v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-timeline-item>
                </v-timeline>
            </v-container>
        </v-main>
    </v-layout>
</template>
