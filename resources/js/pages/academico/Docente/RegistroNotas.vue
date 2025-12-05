<script setup>
import { computed, ref } from 'vue';
import * as XLSX from 'xlsx';

const evaluaciones = ref([
    { key: 'uuid_eval1', title: 'Eval 1', ponderacion: 0.3, visible: true, editable: true },
    { key: 'uuid_eval2', title: 'Eval 2', ponderacion: 0.3, visible: true, editable: true },
    { key: 'uuid_eval3', title: 'Eval 3', ponderacion: 0.4, visible: true, editable: false },
]);

const alumnos = ref([
    { uuid: '1', nombre: 'Carlos', uuid_eval1: 7.5, uuid_eval2: 8.0, uuid_eval3: 9.0 },
    { uuid: '2', nombre: 'Ana', uuid_eval1: 9.0, uuid_eval2: 9.5, uuid_eval3: 9.5 },
    { uuid: '3', nombre: 'Luis', uuid_eval1: 6.0, uuid_eval2: 7.0, uuid_eval3: 8.0 },
]);

const evaluacionesVisibles = computed(() => evaluaciones.value.filter((ev) => ev.visible));

// Cabeceras generadas dinámicamente
const headers = computed(() => [
    { title: 'Alumno', key: 'nombre' },
    ...evaluacionesVisibles.value.map((e) => ({
        title: `${e.title} (${Math.round(e.ponderacion * 100)}%)`,
        key: e.key,
    })),
    { title: 'Promedio', key: 'promedio' },
]);

const valorAnterior = ref(null);

const guardarValorAnterior = (e) => {
    valorAnterior.value = e.target.value;
};

const validarCelda = (e, row, key) => {
    let valor = e.target.value.trim();

    // Si está vacío, revertir
    if (valor === '') {
        row[key] = valorAnterior.value;
        e.target.value = valorAnterior.value;
        return false;
    }

    // Si es número entero de 2 dígitos: autoformato 85 -> 8.5
    if (/^\d{2}$/.test(valor) && valor !== '10') {
        valor = (Number(valor) / 10).toFixed(1);
    }

    // Si es número entero de 1 dígito: autoformato 8 -> 8.0
    if (/^\d$/.test(valor)) {
        valor = Number(valor).toFixed(1);
    }

    // Validación final: permitir 0–10 con 1 decimal
    const regex = /^(10(\.0)?|[0-9](\.[0-9])?)$/;

    if (!regex.test(valor)) {
        row[key] = valorAnterior.value;
        e.target.value = valorAnterior.value;

        return false;
    }

    const num = Number(valor);

    if (num < 0 || num > 10) {
        row[key] = valorAnterior.value;
        e.target.value = valorAnterior.value;
        return false;
    }

    // Valor válido → aplicar
    row[key] = num.toFixed(1);
    e.target.value = num.toFixed(1);

    // si ha cambiado de valor guardar en el servidor
    let valorAnteriorNum = Number(valorAnterior.value);
    if (valorAnteriorNum.toFixed(1) !== num.toFixed(1)) {
        console.log('Guardar en el servidor');
        console.log(row.uuid);
        console.log(key);
    }
    return true;
};

const errorPopup = ref({
    show: false,
    message: '',
    x: 0,
    y: 0,
});

const mostrarErrorCelda = (el, mensaje) => {
    const rect = el.getBoundingClientRect();

    errorPopup.value = {
        show: true,
        message: mensaje,
        x: rect.left + window.scrollX,
        y: rect.bottom + window.scrollY + 4, // un poquito debajo
    };

    // Ocultar después de 3 segundos
    setTimeout(() => {
        errorPopup.value.show = false;
    }, 6000);
};

const exportarExcel = () => {
    // Construir filas con evaluaciones y promedio
    const datos = alumnos.value.map((alumno) => {
        const fila = { Alumno: alumno.nombre };

        evaluaciones.value.forEach((ev) => {
            fila[ev.title] = alumno[ev.key];
        });

        fila['Promedio'] = calcularPromedio(alumno);
        return fila;
    });

    // Crear libro y hoja
    const hoja = XLSX.utils.json_to_sheet(datos);
    const libro = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(libro, hoja, 'Calificaciones');

    // Descargar archivo
    XLSX.writeFile(libro, 'calificaciones.xlsx');
};

const activeRow = ref(null);
const activeCol = ref(null);

const activeCell = ref({ row: null, col: null });

const setActiveCell = (row, col) => {
    activeRow.value = row;
    activeCol.value = col;
};

const findInput = (r, c) => document.querySelector(`input[data-row="${r}"][data-col="${c}"][data-visible="1"]`);

// ------------------ NAVEGACIÓN TIPO EXCEL CON COLUMNAS VISIBLES ------------------
const onExcelNav = (e, fila, key) => {
    const row = Number(e.target.dataset.row);
    const col = Number(e.target.dataset.col);

    let next;

    const findNextVisibleCol = (startCol, step) => {
        let c = startCol + step;
        while (true) {
            const el = findInput(row, c);
            if (el) return el;
            // Si no hay más columnas visibles, se detiene
            if (c < 0 || c > 1000) return null;
            c += step;
        }
    };

    switch (e.key) {
        case 'Escape':
            // Restaurar valor anterior
            fila[key] = valorAnterior.value;

            // Forzar una actualización visual si hace falta
            e.target.value = valorAnterior.value;

            // Quitar selección
            setActiveCell(null, null);

            // Perder el foco real del input
            e.target.blur();
            break;
        case 'Tab':
            next = findNextVisibleCol(col, +1);
            if (!next) {
                next = findInput(row + 1, 0);
            }
            break;
        case 'ArrowRight':
            next = findNextVisibleCol(col, +1);
            break;

        case 'ArrowLeft':
            next = findNextVisibleCol(col, -1);
            break;

        case 'ArrowUp':
            next = findInput(row - 1, col);
            break;

        case 'ArrowDown':
        case 'Enter':
            next = findInput(row + 1, col);
            break;

        default:
            return;
    }

    if (validarCelda(e, fila, key)) {
        errorPopup.value.show = false;
        if (next) {
            e.preventDefault();
            next.focus();
            setActiveCell(Number(next.dataset.row), Number(next.dataset.col));
            next.select();
        } else {
            if (e.key === 'Enter' || e.key === 'Tab') {
                e.preventDefault();

                // Quitar selección
                setActiveCell(null, null);

                // Perder el foco real del input
                e.target.blur();
            }
        }
    } else {
        //mostrar mensaje de error
        mostrarErrorCelda(e.target, 'Valor inválido. Solo 0 a 10 con un decimal. Se restablece el valor anterior.');
    }
};

const aplicarEscalaColor = ref(false);
const aplicarGuiaFilaColumna = ref(false);

const colorEscalaExcel = (valor) => {
    if (valor === '' || valor === null || valor === undefined || !aplicarEscalaColor.value) return 'white';

    const v = Number(valor);
    const min = 0;
    const mid = 6;
    const max = 10;

    let r, g, b;

    if (v <= mid) {
        const t = v / mid;
        r = 245 + (255 - 245) * t;
        g = 168 + (242 - 168) * t;
        b = 168 + (168 - 168) * t;
    } else {
        const t = (v - mid) / (max - mid);
        r = 255 + (182 - 255) * t;
        g = 242 + (232 - 242) * t;
        b = 168 + (176 - 168) * t;
    }

    return `rgb(${r}, ${g}, ${b})`;
};

// ------------------ PROMEDIO PONDERADO ------------------
function calcularPromedio(item) {
    let suma = 0;
    evaluaciones.value.forEach((ev) => {
        const v = Number(item[ev.key]) || 0;
        suma += v * (ev.ponderacion || 0);
    });
    // mostrar con 1 decimal
    return (Math.round(suma * 10) / 10).toFixed(1);
}
</script>
<template>
    <v-container fluid class="pa-4">
        <v-row class="mb-2" align="center" justify="space-between">
            <v-col cols="6">
                <h3>Hoja tipo Excel - Ingreso de calificaciones</h3>
            </v-col>
            <v-btn color="green" class="mb-4" @click="exportarExcel"> Exportar a Excel </v-btn>
            <v-checkbox v-model="aplicarEscalaColor" label="Escala de color" hide-details />
            <v-checkbox v-model="aplicarGuiaFilaColumna" label="Guía de fila y columna" hide-details />
            <v-menu>
                <template #activator="{ props }">
                    <v-btn v-bind="props" color="primary">Columnas</v-btn>
                </template>

                <v-list>
                    <v-list-item v-for="ev in evaluaciones" :key="ev.key">
                        <v-checkbox v-model="ev.visible" :label="ev.title" hide-details />
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-row>

        <v-data-table :headers="headers" :items="alumnos" class="excel-table" hide-default-header :items-per-page="-1" hide-default-footer>
            <!-- ENCABEZADO -->
            <template #thead>
                <thead>
                    <tr class="excel-header">
                        <th class="excel-header excel-first-col" :class="{ 'excel-header-active': activeCol === -1 }">Alumno</th>

                        <th
                            v-for="(ev, colIndex) in evaluacionesVisibles"
                            :key="ev.key"
                            class="excel-header"
                            :class="{ 'excel-header-active': activeCol === colIndex, 'text-disabled': !ev.editable }"
                        >
                            {{ ev.title }} ({{ ev.ponderacion }}%)
                        </th>

                        <th class="excel-header">Promedio</th>
                    </tr>
                </thead>
            </template>

            <!-- CUERPO -->
            <template #body="{ items }">
                <tr v-for="(row, rowIndex) in items" :key="rowIndex" class="excel-row">
                    <td class="excel-header" :class="{ 'excel-row-header-active': activeRow === rowIndex }">
                        {{ row.nombre }}
                    </td>

                    <td
                        v-for="(ev, colIndex) in evaluacionesVisibles"
                        :key="ev.key"
                        class="excel-cell"
                        :class="{
                            'excel-row-highlight': activeRow === rowIndex && aplicarGuiaFilaColumna,
                            'excel-col-highlight': activeCol === colIndex && aplicarGuiaFilaColumna,
                            'excel-cell-active': activeRow === rowIndex && activeCol === colIndex,
                        }"
                        :style="{ background: colorEscalaExcel(row[ev.key]), 'text-align': 'right' }"
                    >
                        <input
                            v-if="ev.editable"
                            class="excel-input"
                            v-model="row[ev.key]"
                            :data-row="rowIndex"
                            :data-col="colIndex"
                            :data-visible="ev.visible ? 1 : 0"
                            @keydown="(e) => onExcelNav(e, row, ev.key)"
                            @focus="
                                (e) => {
                                    setActiveCell(rowIndex, colIndex);
                                    guardarValorAnterior(e);
                                }
                            "
                            @click="() => setActiveCell(rowIndex, colIndex)"
                            style="text-align: right"
                        />
                        <span v-else class="text-disabled">{{ row[ev.key] }}</span>
                    </td>

                    <td
                        class="excel-header text-h6"
                        :style="{ background: colorEscalaExcel(calcularPromedio(row)), 'text-align': 'right' }"
                        :class="{ 'excel-row-header-right-active': activeRow === rowIndex }"
                    >
                        {{ calcularPromedio(row) }}
                    </td>
                </tr>
            </template>
        </v-data-table>
    </v-container>
    <div v-if="errorPopup.show" class="error-popup" :style="{ left: errorPopup.x + 'px', top: errorPopup.y + 'px' }">
        {{ errorPopup.message }}
    </div>
</template>

<style scoped>
.excel-table {
    border-collapse: collapse;
    width: max-content;
    background: white;
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 16px;
}

/* ────── HEADERS ─────── */

.excel-header {
    background: linear-gradient(to bottom, #ffffff, #e2e2e2);
    box-shadow:
        inset 0 -2px 0 #c8c8c8,
        0 4px 10px rgba(0, 0, 0, 0.22);
}

/* Freeze first column header also */
.excel-header:first-child {
    left: 0;
    z-index: 6;
}

/* ────── CELLS ─────── */
.excel-cell {
    border: 1px solid #d0d0d0;
    padding: 0;
    position: relative;
    height: 32px;
}

/* Freeze first column */
.excel-cell:first-child {
    position: sticky;
    left: 0;
    background: white;
    z-index: 4;
}

/* ────── INPUTS (Editable cells) ─────── */
.excel-input {
    width: 100%;
    height: 100%;
    border: none;
    padding: 4px 6px;
    outline: none;
    background: transparent;
    font-size: 14px;
    font-family: 'Segoe UI', Arial, sans-serif;
}

/* Hover style */
.excel-row:hover .excel-cell {
    /*background: #eef6ff; /* Light blue hover */
}

/* Selected look (optional if you want multi-select later) */
.excel-cell.selected {
    outline: 2px solid #4a90e2;
    z-index: 10;
}

/* Celda activa */
.excel-cell-active {
    outline: 2px solid #4a90e2 !important; /* borde azul Excel */
    z-index: 20;
    position: relative;
}

/* Resaltar fila completa */
.excel-row-highlight {
    background-color: #e8f1ff !important; /* azul claro Excel */
}

/* Resaltar columna completa */
.excel-col-highlight {
    background-color: #e8f1ff !important;
}

/* No cubrir el fondo de la celda activa */
.excel-cell-active.excel-col-highlight,
.excel-cell-active.excel-row-highlight {
    background: white !important;
}

/* Encabezado resaltado cuando la columna está activa */
.excel-header-active {
    background: #dbe6ff !important; /* Azul claro Excel */
    color: #000;
    font-weight: 700;
    box-shadow: inset 0 -2px 0 #4a90e2; /* Línea azul inferior */
}

/* Encabezado resaltado cuando la fila está activa */
.excel-row-header-active {
    background: #dbe6ff !important; /* Azul claro Excel */
    color: #000;
    font-weight: 700;
    box-shadow: inset -2px 0 0 #4a90e2; /* Línea azul derecha */
}

/* Encabezado resaltado cuando la fila está activa */
.excel-row-header-right-active {
    background: #dbe6ff !important; /* Azul claro Excel */
    color: #000;
    font-weight: 700;
    box-shadow: inset 2px 0 0 #4a90e2; /* Línea azul izquierda */
}

.error-popup {
    position: absolute;
    background: #ffdddd;
    color: #b00000;
    border: 1px solid #d55;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 13px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    z-index: 9999;
    pointer-events: none;
    white-space: nowrap;
}
</style>
