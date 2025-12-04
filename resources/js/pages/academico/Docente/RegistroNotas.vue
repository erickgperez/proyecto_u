<template>
  <v-container fluid class="pa-4">
    <v-row class="mb-2" align="center" justify="space-between">
      <v-col cols="6">
        <h3>Hoja tipo Excel - Ingreso de calificaciones</h3>
      </v-col>
      
      <v-menu>
  <template #activator="{ props }">
    <v-btn v-bind="props" color="primary">Columnas</v-btn>
  </template>

  <v-list>
    <v-list-item
      v-for="ev in evaluaciones"
      :key="ev.key"
    >
      <v-checkbox
        v-model="ev.visible"
        :label="ev.title"
        hide-details
      />
    </v-list-item>
  </v-list>
</v-menu>
    </v-row>

    <v-data-table
  :headers="headers"
  :items="alumnos"
  class="excel-table"
  hide-default-header
>

  <!-- ENCABEZADO -->
  <template #thead>
  <thead>
    <tr class="excel-header">
      <th class="excel-header excel-first-col">Alumno</th>

      <th
        v-for="ev in evaluacionesVisibles"
        :key="ev.key"
        class="excel-header"
      >
        {{ ev.title }} ({{ ev.ponderacion }}%)
      </th>

      <th class="excel-header">Promedio</th>
    </tr>
  </thead>
</template>

  <!-- CUERPO -->
  <template #body="{ items }">
      <tr
        v-for="(row, rowIndex) in items"
        :key="rowIndex"
        class="excel-row"
      >
        <td class="excel-cell">
          {{ row.nombre }}
        </td>

        <td
          v-for="(ev, colIndex) in evaluacionesVisibles"
          :key="ev.key"
          class="excel-cell"
        >
          <input
            class="excel-input"
            v-model="row[ev.key]"
            :data-row="rowIndex"
            :data-col="colIndex"
            :data-visible="ev.visible ? 1 : 0"            
            @keydown="onExcelNav"
            @focus="guardarValorAnterior"
            @blur="(e) => validarCelda(e, row, ev.key)"
          >
        </td>

        <td class="excel-cell">
          {{ calcularPromedio(row) }}
        </td>

      </tr>
  </template>

</v-data-table>



  </v-container>
</template>

<script setup>
import { ref, computed, nextTick } from "vue";

const evaluaciones = ref([
  { key: "eval1", title: "Eval 1", ponderacion: 0.3, visible: true },
  { key: "eval2", title: "Eval 2", ponderacion: 0.3, visible: true },
  { key: "eval3", title: "Eval 3", ponderacion: 0.4, visible: true },
]);

const alumnos = ref([
  { nombre: "Carlos", eval1: 7.5, eval2: 8.0, eval3: 9.0 },
  { nombre: "Ana", eval1: 9.0, eval2: 9.5, eval3: 9.5 },
  { nombre: "Luis", eval1: 6.0, eval2: 7.0, eval3: 8.0 },
]);

const evaluacionesVisibles = computed(() =>
  evaluaciones.value.filter(ev => ev.visible)
);

// Cabeceras generadas dinámicamente
const headers = computed(() => [
  { title: "Alumno", key: "nombre" },
  ...evaluacionesVisibles.value.map((e) => ({
    title: `${e.title} (${Math.round(e.ponderacion * 100)}%)`,
    key: e.key,
  })),
  { title: "Promedio", key: "promedio" },
]);

const valorAnterior = ref(null);

const guardarValorAnterior = (e) => {
  valorAnterior.value = e.target.value;
};

const validarCelda = (e, row, key) => {
  let valor = e.target.value.trim();

  // Si está vacío, revertir
  if (valor === "") {
    row[key] = valorAnterior.value;
    e.target.value = valorAnterior.value;
    return;
  }

  // Si es número entero de 2 dígitos: autoformato 85 -> 8.5
  if (/^\d{2}$/.test(valor)) {
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
    return;
  }

  const num = Number(valor);

  if (num < 0 || num > 10) {
    row[key] = valorAnterior.value;
    e.target.value = valorAnterior.value;
    return;
  }

  // Valor válido → aplicar
  row[key] = num.toFixed(1);
  e.target.value = num.toFixed(1);
};


// ------------------ NAVEGACIÓN TIPO EXCEL CON COLUMNAS VISIBLES ------------------
const onExcelNav = (e) => {
  const row = Number(e.target.dataset.row);
  const col = Number(e.target.dataset.col);

  const findInput = (r, c) =>
    document.querySelector(`input[data-row="${r}"][data-col="${c}"][data-visible="1"]`);

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
    case "ArrowRight":
      next = findNextVisibleCol(col, +1);
      break;

    case "ArrowLeft":
      next = findNextVisibleCol(col, -1);
      break;

    case "ArrowUp":
      next = findInput(row - 1, col);
      break;

    case "ArrowDown":
    case "Enter":
      next = findInput(row + 1, col);
      break;

    default:
      return;
  }

  if (next) {
    e.preventDefault();
    next.focus();
    next.select();
  }
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

<style scoped>
.excel-table {
  border-collapse: collapse;
  width: 100%;
}

.excel-table table {
  table-layout: fixed !important;
  width: 100% !important;
  border-collapse: collapse !important;
}


.excel-header,
.excel-cell {
  padding: 4px 6px !important;
  border: 1px solid #d1d1d1 !important;
  font-size: 14px !important;
  text-align: center;
}

.excel-header {
  background: #1e4e79 !important;
  color: white !important;
  font-weight: bold !important;
  position: sticky;
  top: 0;
  z-index: 5;
}

.excel-first-col {
  position: sticky;
  left: 0;
  z-index: 4;
  background: white;
}


.excel-row:nth-child(even) {
  background-color: #f3f6fb;
}

.excel-cell {
  border: 1px solid #c0c0c0;
  padding: 0;
}

.excel-input {
  width: 100%;
  border: none;
  padding: 4px;
  outline: none;
}

.excel-input:focus {
  outline: 2px solid #4a90e2;
  background: #fffef0;
}


</style>
