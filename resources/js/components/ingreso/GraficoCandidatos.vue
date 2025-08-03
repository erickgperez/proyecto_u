<template>
    <div class="p-4">
        <h2 class="mb-4 text-xl font-bold">Tabla pivote con filtros y gráfico</h2>

        <!-- Selectores principales -->
        <div class="mb-4 flex flex-wrap gap-4">
            <div>
                <label class="block font-semibold">Fila (row):</label>
                <select v-model="rowKey" class="rounded border p-1">
                    <option disabled value="">-- Seleccionar --</option>
                    <option v-for="col in columns" :key="col" :value="col">{{ col }}</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold">Columna (column):</label>
                <select v-model="colKey" class="rounded border p-1">
                    <option disabled value="">-- Seleccionar --</option>
                    <option v-for="col in columns" :key="col" :value="col" :disabled="col === rowKey">
                        {{ col }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block font-semibold">Tipo de gráfico:</label>
                <select v-model="chartType" class="rounded border p-1">
                    <option value="heatmap">Heatmap</option>
                    <option value="bar">Barras</option>
                    <option value="line">Líneas</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block font-semibold">Campo numérico:</label>
            <select v-model="metricField" class="rounded border p-1">
                <option disabled value="">-- Seleccionar --</option>
                <option v-for="col in numericFields" :key="col" :value="col">
                    {{ col }}
                </option>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Métrica:</label>
            <select v-model="aggregation" class="rounded border p-1">
                <option value="sum">Suma</option>
                <option value="count">Conteo</option>
                <option value="avg">Promedio</option>
            </select>
        </div>

        <!-- Filtros adicionales -->
        <div v-if="filterableFields.length" class="mb-4 flex flex-wrap gap-4">
            <div v-for="field in filterableFields" :key="field" class="flex flex-col">
                <label class="font-semibold">{{ field }}</label>
                <select v-model="filters[field]" class="rounded border p-1">
                    <option value="">(Todos)</option>
                    <option v-for="val in getUniqueValues(field)" :key="val" :value="val">
                        {{ val }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Tabla pivote -->
        <table class="mb-6 table-auto border-collapse border border-gray-400">
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
        <div ref="plotlyContainer" class="mx-auto w-full max-w-3xl"></div>
    </div>
</template>

<script setup>
import Plotly from 'plotly.js-dist-min';
import { computed, onMounted, ref, watch } from 'vue';

// 🔸 Datos de ejemplo
const data = [
    { color: 'blue', shape: 'circle', count: 10, category: 'A' },
    { color: 'red', shape: 'triangle', count: 20, category: 'B' },
    { color: 'blue', shape: 'triangle', count: 15, category: 'A' },
    { color: 'red', shape: 'circle', count: 5, category: 'B' },
    { color: 'blue', shape: 'circle', count: 8, category: 'A' },
    { color: 'red', shape: 'circle', count: 12, category: 'A' },
];

// 🔹 Variables reactivas
const columns = Object.keys(data[0]);
const rowKey = ref('color');
const colKey = ref('shape');
const chartType = ref('heatmap');
// Selector de campo métrico y operación
const metricField = ref('count');
const aggregation = ref('sum');

// 🔹 Filtros dinámicos
const filters = ref({});

// 🔹 Campos filtrables (no usados como row ni col)
const filterableFields = computed(() => columns.filter((c) => c !== rowKey.value && c !== colKey.value && c !== 'count'));

// 🔹 Valores únicos por campo
function getUniqueValues(field) {
    return [...new Set(data.map((item) => item[field]))];
}

// 🔹 Datos filtrados según filtros activos
const filteredData = computed(() => {
    return data.filter((item) => Object.entries(filters.value).every(([field, selected]) => (selected ? item[field] === selected : true)));
});

// 🔹 Valores únicos de fila y columna
const uniqueRowValues = computed(() => [...new Set(filteredData.value.map((item) => item[rowKey.value]))]);
const uniqueColValues = computed(() => [...new Set(filteredData.value.map((item) => item[colKey.value]))]);

// Detectar campos numéricos
const numericFields = columns.filter((c) => typeof data.find((item) => typeof item[c] === 'number') !== 'undefined');

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
    const z = uniqueRowValues.value.map((row) => uniqueColValues.value.map((col) => getCellValue(row, col)));

    let traces = [];
    const layout = {
        title: `Gráfico: ${chartType.value}`,
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

// 🔹 Actualizar al cambiar parámetros
watch([rowKey, colKey, chartType, filters, aggregation, metricField], renderChart, { deep: true });

onMounted(renderChart);
</script>
