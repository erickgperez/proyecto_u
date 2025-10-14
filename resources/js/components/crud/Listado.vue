<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import type { Header, SortBy } from '@/types/tipos';
import { saveAs } from 'file-saver';
import { computed, PropType, ref } from 'vue';
import * as XLSX from 'xlsx';

const { hasPermission, hasAnyPermission } = usePermissions();

const emit = defineEmits(['action', 'selectItem']);

const props = defineProps({
    items: {
        type: Array,
        required: true,
        default: () => [],
    },
    headers: {
        type: Array as PropType<Header[]>,
        required: true,
        default: () => [],
    },
    sortBy: {
        type: Array as PropType<SortBy[]>,
        required: true,
        default: () => [],
    },

    titleList: {
        type: String,
        required: true,
        default: () => '',
    },
    permisoCrear: {
        type: String,
        required: true,
        default: () => '',
    },
    permisoExportar: {
        type: String,
        required: true,
        default: () => '',
    },
    sheetName: {
        type: String,
        required: true,
        default: () => '',
    },
    fileName: {
        type: String,
        required: true,
        default: () => '',
    },
});

const search = ref('');

const exportToExcel = () => {
    const worksheet = XLSX.utils.json_to_sheet(props.items);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, props.sheetName);

    // Generate a binary string
    const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

    // Create a Blob and save the file
    const data = new Blob([excelBuffer], { type: 'application/octet-stream' });
    saveAs(data, props.fileName + '.xlsx');
};

function emitirAccion(accion: string) {
    emit('action', accion);
}

const headersFiltered = computed(() => {
    return props.headers.filter((header: Header) => header.key != 'actions');
});
</script>
<template>
    <v-card>
        <v-card-title class="d-flex align-center border-b-md pe-2">
            <v-icon icon="mdi-format-list-text"></v-icon> &nbsp; {{ props.titleList }}
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
            <v-btn
                v-if="hasPermission(props.permisoCrear)"
                icon="mdi-table-plus"
                color="success"
                class="ml-2"
                :title="$t('_crear_nuevo_registro_')"
                @click="emitirAccion('new')"
            ></v-btn>
            <v-btn
                v-if="hasPermission(props.permisoExportar)"
                icon="mdi-file-export-outline"
                color="primary"
                variant="tonal"
                class="ma-2"
                :title="$t('_exportar_')"
                @click="exportToExcel"
            ></v-btn>
        </v-card-title>

        <v-data-table
            v-model:search="search"
            :headers="headers"
            :items="props.items"
            border="primary thin"
            class="w-100"
            :sort-by="sortBy"
            multi-sort
            hover
            striped="odd"
        >
            <template v-for="header in headersFiltered" v-slot:[`item.${header.key}`]="{ item }">
                <slot :name="`item.${header.key}`" :value="item[header.key]" :item="item">
                    {{ item[header.key] }}
                </slot>
            </template>
            <template v-slot:item.actions="{ item }">
                <div class="d-flex ga-2 justify-center">
                    <v-icon color="green-darken-2" icon="mdi-chevron-right-circle-outline" size="x-large" @click="emit('selectItem', item)"></v-icon>
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>
