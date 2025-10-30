<script setup lang="ts">
import { Solicitud, SortBy } from '@/types/tipos';
import { PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
    solicitudes: {
        type: Array as PropType<Solicitud[]>,
        required: true,
        default: () => [],
    },
});

const emit = defineEmits(['seleccionar']);

const headers = [
    { title: t('aspirante._nie_'), key: 'nie' },
    { title: t('_nombre_'), key: 'nombre' },
    { title: t('aspirante._nota_'), key: 'nota' },
    { title: t('_sector_'), key: 'sector' },
    { title: t('aspirante._seleccionado_'), key: 'seleccionado' },
    { title: t('aspirante._opciones_'), key: 'opciones' },
];
const search = ref('');

const sortBy: SortBy[] = [
    { key: 'nota', order: 'desc' },
    { key: 'nombre', order: 'asc' },
];
</script>
<template>
    <v-card-title class="d-flex align-center border-b-md pe-2">
        <v-spacer></v-spacer>

        <v-text-field
            v-model="search"
            density="compact"
            :label="$t('_buscar_')"
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            rounded="xl"
            flat
            hide-details
            single-line
        ></v-text-field>
    </v-card-title>
    <v-data-table
        v-model:search="search"
        :headers="headers"
        :items="props.solicitudes"
        :sort-by="sortBy"
        density="compact"
        border="primary thin"
        class="w-100"
        multi-sort
        hover
        striped="odd"
    >
        <template v-slot:item="{ item }">
            <tr
                class="text-no-wrap"
                :class="{
                    'bg-green-lighten-4': item.seleccionado,
                }"
            >
                <td>{{ item.nie }}</td>
                <td>{{ item.nombre }}</td>
                <td>{{ item.nota }}</td>
                <td>{{ item.sector }}</td>
                <td>
                    <v-checkbox-btn v-model="item.seleccionado" :ripple="false" @update:modelValue="emit('seleccionar', item)"></v-checkbox-btn>
                </td>
                <td>
                    <v-list density="compact" bg-color="transparent">
                        <v-list-item v-if="item.PRIMERA_OPCION">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-numeric-1"></v-icon>
                            </template>

                            <v-list-item-title>
                                {{ item.PRIMERA_OPCION.carrera }}
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="item.SEGUNDA_OPCION">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-numeric-2"></v-icon>
                            </template>

                            <v-list-item-title>
                                {{ item.SEGUNDA_OPCION.carrera }}
                            </v-list-item-title>
                        </v-list-item>
                        <v-list-item v-if="item.TERCERA_OPCION">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-numeric-3"></v-icon>
                            </template>

                            <v-list-item-title>
                                {{ item.TERCERA_OPCION?.carrera }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </td>
            </tr>
        </template>
    </v-data-table>
</template>
