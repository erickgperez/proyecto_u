<script setup lang="ts">
import { useDeepFilter } from '@/composables/useDeepFilter';
import { usePermissions } from '@/composables/usePermissions';
import type { Header, SortBy } from '@/types/tipos';
import { saveAs } from 'file-saver';
import { computed, PropType, ref } from 'vue';
import * as XLSX from 'xlsx';

const { hasPermission } = usePermissions();

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
    groupBy: Array,
    ocultarCrear: Boolean,
});

const search = ref('');

// Usa modo "AND" (todas las palabras deben aparecer) modo 'OR' cumple alguna de las palabras
const { deepFilter, getByPath } = useDeepFilter({ multiWordMode: 'AND', headers: props.headers });

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

const localItems = ref(props.items);

// Build unique options per group
const groupOptions = computed(() => {
    return props.groupBy?.map((g: any) => {
        const key = g.key;
        const values = new Set<string>();

        for (const item of props.items) {
            const raw = getByPath(item, key);
            if (raw != null) values.add(String(raw));
        }

        return { key, values: [...Array.from(values).sort()], title: g.title };
    });
});

// selección del usuario por grupo (multi-select)
const selectedGroups = ref<Record<string, string[] | null>>({});

// filtrado por las selecciones de grupo (usa getByPath)
const filteredByGroups = computed(() => {
    if (props.groupBy) {
        return localItems.value.filter((item) => {
            return props.groupBy?.every((g) => {
                const sel = selectedGroups.value[g.key];
                if (!sel || sel.length === 0) return true;
                const value = getByPath(item, g.key);
                return sel.includes(String(value));
            });
        });
    } else {
        return localItems.value;
    }
});
</script>
<template>
    <v-card class="rounded-t-xl">
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
                v-if="hasPermission(props.permisoCrear) && !ocultarCrear"
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
            <!-- Control de grupo arriba -->
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="12">
                    <div class="d-flex ga-4 flex-wrap">
                        <div v-for="group in groupOptions" :key="group.key" class="d-flex flex-column">
                            <v-autocomplete
                                v-model="selectedGroups[group.key]"
                                :items="group.values"
                                :label="`Filtrar por ${group.title}`"
                                multiple
                                clearable
                                chips
                                density="compact"
                            />
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-card-text>
        <v-data-table
            v-model:search="search"
            :headers="headers"
            :items="filteredByGroups"
            border="primary thin"
            class="w-100"
            :sort-by="sortBy"
            density="compact"
            :custom-filter="deepFilter"
            fixed-header
            multi-sort
            hover
            striped="odd"
        >
            <template v-for="(_, name) in $slots" v-slot:[name]="slotProps">
                <slot :name="name" v-bind="slotProps"></slot>
            </template>
            <template v-slot:item.actions="{ item }">
                <div class="d-flex ga-2 justify-center">
                    <v-icon color="green-darken-2" icon="mdi-chevron-right-circle-outline" size="x-large" @click="emit('selectItem', item)"></v-icon>
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>
