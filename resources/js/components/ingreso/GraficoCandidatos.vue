<template>
    <div class="p-4">
        <!-- <h2 class="mb-4 text-xl font-bold">Tabla pivote con filtros y gráfico</h2>-->

        <!-- Selectores principales -->
        <div class="mb-4 flex">
            <v-select v-model="rowKey" :label="$t('_fila_')" :items="columns"></v-select>

            <v-select v-model="colKey" :label="$t('_columna_')" :items="columns"></v-select>

            <v-select
                v-model="chartType"
                :label="$t('_tipo_visualizacion_')"
                item-title="label"
                :items="[
                    { value: 'bar', label: $t('_barras_') },
                    { value: 'line', label: $t('_lineas_') },
                    { value: 'tabla', label: $t('_tabla_datos_') },
                ]"
            ></v-select>
        </div>

        {{ $t('_filtros_') }}
        <v-divider class="border-opacity-100" color="info" :thickness="4"></v-divider>
        <!-- Filtros adicionales -->
        <v-row v-if="filterableFields.length">
            <v-col v-for="field in filterableFields" :key="field">
                <v-select v-model="filters[field]" :items="getUniqueValues(field)" :label="field" clearable multiple></v-select>
            </v-col>
        </v-row>

        <!-- Tabla pivote -->
        <table v-show="chartType == 'tabla'" class="w-full">
            <thead>
                <tr>
                    <th class="border border-gray-400 px-2 py-1">{{ rowKey }}</th>
                    <th v-for="col in uniqueColValues" :key="col" class="border border-gray-400 px-2 py-1">
                        {{ col }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in uniqueRowValues" :key="row">
                    <td class="border border-gray-400 px-2 py-1">{{ row }}</td>
                    <td v-for="col in uniqueColValues" :key="col" class="border border-gray-400 px-2 py-1 text-center">
                        {{ getCellValue(row, col) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Gráfico -->
        <div v-show="chartType != 'tabla'" ref="plotlyContainer" class="mx-auto w-full"></div>
    </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import Plotly from 'plotly.js-dist-min';

import { computed, onMounted, ref, watch } from 'vue';

// 🔸 Datos de ejemplo
const data = ref([]);

// 🔹 Variables reactivas
const rowKey = ref('sector');
const colKey = ref('departamento');
const chartType = ref('bar');
// Selector de campo métrico y operación
const metricField = ref('candidatos');
const aggregation = ref('sum');

// 🔹 Filtros dinámicos
const filters = ref({});

// 🔹 Campos filtrables (no usados como row ni col)
const filterableFields = computed(() =>
    columns.value.filter((c) => c !== rowKey.value && c !== colKey.value && c !== 'invitados' && c !== 'candidatos'),
);

// 🔹 Valores únicos por campo
function getUniqueValues(field) {
    return [...new Set(data.value.map((item) => item[field]))];
}

// 🔹 Datos filtrados según filtros activos
const columns = computed(() => {
    if (data.value.length > 0) {
        return Object.keys(data.value[0]).filter((c) => c !== 'invitados' && c !== 'candidatos');
    } else {
        return [];
    }
});
const filteredData = computed(() => {
    return data.value.filter((item) =>
        Object.entries(filters.value).every(([field, selected]) => (selected && !selected.length == 0 ? selected.includes(item[field]) : true)),
    );
});

// 🔹 Valores únicos de fila y columna
const uniqueRowValues = computed(() => [...new Set(filteredData.value.map((item) => item[rowKey.value]))]);
const uniqueColValues = computed(() => [...new Set(filteredData.value.map((item) => item[colKey.value]))]);

// Detectar campos numéricos
const numericFields = columns.value.filter((c) => typeof data.value.find((item) => typeof item[c] === 'number') !== 'undefined');

// Calcular valor por celda con la métrica seleccionada
function getCellValue(rowVal, colVal) {
    const matching = filteredData.value.filter((item) => item[rowKey.value] === rowVal && item[colKey.value] === colVal);

    if (aggregation.value === 'count') return matching.length;

    const values = matching.map((item) => item[metricField.value] ?? 0);

    if (aggregation.value === 'sum') {
        return values.reduce((a, b) => a + b, 0);
    } else if (aggregation.value === 'avg') {
        const total = values.reduce((a, b) => a + b, 0);
        return values.length ? (total / values.length).toFixed(2) : 0;
    }

    return 0;
}

// 🔹 Contenedor del gráfico
const plotlyContainer = ref();

function renderChart() {
    if (chartType.value != 'tabla') {
        const z = uniqueRowValues.value.map((row) => uniqueColValues.value.map((col) => getCellValue(row, col)));

        let traces = [];
        const layout = {
            title: `Candidatos`,
            xaxis: { title: colKey.value },
            yaxis: { title: rowKey.value },
            margin: { t: 40 },
        };

        if (chartType.value === 'heatmap') {
            traces = [
                {
                    z,
                    x: uniqueColValues.value,
                    y: uniqueRowValues.value,
                    type: 'heatmap',
                    colorscale: 'YlGnBu',
                },
            ];
        } else {
            traces = uniqueRowValues.value.map((row, i) => ({
                x: uniqueColValues.value,
                y: uniqueColValues.value.map((col) => z[i][uniqueColValues.value.indexOf(col)]),
                type: chartType.value,
                name: row,
            }));
        }

        Plotly.newPlot(plotlyContainer.value, traces, layout, { responsive: true });
    }
}

async function getResumenCandidatos() {
    data.value = [];
    try {
        const response = await axios.get(route('ingreso-bachillerato-candidatos-resumen'));
        data.value = response.data;
        renderChart();
    } catch (error) {
        console.error(error);
    }
}

// 🔹 Actualizar al cambiar parámetros
watch([rowKey, colKey, chartType, filters, aggregation, metricField], renderChart, { deep: true });

onMounted(() => {
    getResumenCandidatos();
});
</script>
