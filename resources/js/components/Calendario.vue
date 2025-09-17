<script setup lang="ts">
import { PropType, ref } from 'vue';
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

interface Actividad {
    id: number | null;
    step: string;
    name: string;
    start: Date | null;
    end: Date | null;
    icon: string;
}

const props = defineProps({
    actividades: {
        type: Array as PropType<Actividad[]>,
        required: true,
        default: () => [],
    },
});

const actividadesLocal = ref([...props.actividades]);
</script>

<template>
    <v-layout>
        <v-app-bar color="primary" class="bg-transparent" density="compact" rounded="t-lg">
            <v-toolbar-title>{{ $t('convocatoria._listado_actividades_') }}</v-toolbar-title>

            <template v-slot:append>
                <v-btn icon="mdi-calendar-plus" :title="$t('calendario._agregar_actividad_')"></v-btn>
            </template>
        </v-app-bar>

        <v-main>
            <v-container>
                <v-timeline align="start">
                    <v-timeline-item v-for="(item, i) in actividadesLocal" :key="i" :dot-color="getColor(item, '')" fill-dot :icon="item.icon">
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
