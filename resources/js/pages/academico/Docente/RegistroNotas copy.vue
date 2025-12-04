<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
const props = defineProps(['expedientes', 'oferta']);

import { ref, nextTick } from 'vue'

const headers = [
  { title: 'Alumno', key: 'name', sortable: false },
  { title: 'Calificación', key: 'score', sortable: false },
  { title: '', key: 'actions', sortable: false, width: 60 }
]

const students = ref([
  { name: '', score: null }
])

const rules = {
  range: v => (v == null || (v >= 0 && v <= 100)) || '0-100'
}

function addRow () {
  students.value.push({ name: '', score: null })
  nextTick(() => {
    const inputs = document.querySelectorAll('table input')
    inputs[inputs.length - 2]?.focus()
  })
}

function removeRow (idx) {
  students.value.splice(idx, 1)
  if (!students.value.length) addRow()
}

function focusNext (rowIndex, field) {
  // Salta al siguiente campo “nombre” cuando se presiona Tab en la calificación
  if (field === 'score') {
    const nextIdx = rowIndex + 1
    if (nextIdx >= students.value.length) {
      addRow()
    } else {
      nextTick(() => {
        const table = document.querySelector('table')
        const target = table.querySelectorAll('input')[nextIdx * 2]
        target?.focus()
      })
    }
  }
}
</script>

<style scoped>
/* Opcional: hacer que los inputs parezcan celdas de Excel */
.v-data-table :deep(input) {
  border: none;
  box-shadow: none;
  background: transparent;
  padding: 4px 8px;
}
</style>
<template>
  <v-container>
    <v-card>
      <v-card-title class="text-h6">
        Ingreso de calificaciones
        <v-spacer />
        <v-btn
          color="primary"
          variant="tonal"
          prepend-icon="mdi-plus"
          @click="addRow"
        >
          Agregar alumno
        </v-btn>
      </v-card-title>

      <v-divider />

      <v-data-table
        :headers="headers"
        :items="students"
        hide-default-footer
        class="elevation-0"
        density="compact"
      >
        <!-- Columna Nombre -->
        <template #item.name="{ item, index }">
          <v-text-field
            v-model="item.name"
            variant="underlined"
            density="compact"
            hide-details
            @keydown.tab="focusNext(index, 'name')"
          />
        </template>

        <!-- Columna Calificación -->
        <template #item.score="{ item, index }">
          <v-text-field
            v-model.number="item.score"
            variant="underlined"
            density="compact"
            hide-details
            type="number"
            :rules="[rules.range]"
            @keydown.tab="focusNext(index, 'score')"
          />
        </template>

        <!-- Columna Eliminar -->
        <template #item.actions="{ index }">
          <v-btn
            icon="mdi-delete"
            size="small"
            variant="text"
            color="error"
            @click="removeRow(index)"
          />
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>