<script setup lang="ts">
import ConvocatoriaForm from '@/components/ingreso/ConvocatoriaForm.vue';
import ConvocatoriaShow from '@/components/ingreso/ConvocatoriaShow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { saveAs } from 'file-saver';
import { computed, PropType, ref } from 'vue';
import { useDate } from 'vuetify';
import * as XLSX from 'xlsx';

const step = ref(1);

const selectedItem = ref<Item>();
const selectedAction = ref('');

const date = useDate();

const search = ref('');

const exportToExcel = () => {
    const worksheet = XLSX.utils.json_to_sheet(props.items);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, sheetName.value);

    // Generate a binary string
    const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

    // Create a Blob and save the file
    const data = new Blob([excelBuffer], { type: 'application/octet-stream' });
    saveAs(data, fileName.value + '.xlsx');
};

//const items = ref([]);

//const dialog = shallowRef(false);

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
interface Item {
    id: number | null;
    fecha: Date | null;
    nombre: string;
    descripcion: string;
    cuerpo_mensaje: string;
    afiche: string | null;
}

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
});

//const localItems = reactive<Item[]>([]);
const localItems = ref([...props.items]); // Create a shallow copy

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_convocatorias');
const fileName = ref('convocatorias');

const selectedItemLabel = computed(() => selectedItem.value?.nombre ?? '');

// Título del listado
const titleList = ref('Listado de convocatorias');

const headers = [
    { title: 'fecha', key: 'fecha' },
    { title: 'Nombre', key: 'nombre', align: 'start' },
    { title: 'Descripción', key: 'descripcion' },
    { title: 'Acciones', key: 'actions', align: 'end' },
];

const sortBy = [
    { key: 'fecha', order: 'asc' },
    { key: 'nombre', order: 'asc' },
];

function remove(id: number) {
    const index = items.value.findIndex((item) => item.id === id);
    items.value.splice(index, 1);
}

const selectItem = (item: any) => {
    selectedItem.value = item;

    step.value++;
};

const selectAction = (accion: string) => {
    selectedAction.value = accion;
    step.value++;
};

const handleFormSave = (data: Item) => {
    if (selectedAction.value == 'edit') {
        const index = localItems.value.findIndex((item) => item.id === data.id);

        localItems.value[index] = data;
    } else {
        localItems.value.push(data);
        step.value = 1;
    }
};
</script>

<template>
    <Head title="Convocatoria"> </Head>
    <AppLayout :titulo="$t('_administrar_convocatoria_')" :subtitulo="$t('_permite_gestionar_datos_convocatorias_')" icono="mdi-wrench-clock">
        <v-window v-model="step" class="h-auto w-100">
            <v-window-item :value="1">
                <v-card>
                    <v-card-title class="d-flex align-center pe-2">
                        <v-icon icon="mdi-format-list-text"></v-icon> &nbsp; {{ titleList }}
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
                            icon="mdi-table-plus"
                            color="success"
                            class="ml-2"
                            :title="$t('_crear_convocatoria_')"
                            @click="
                                selectAction('new');
                                step = 3;
                            "
                        ></v-btn>
                        <v-btn
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
                        :items="localItems"
                        border="primary thin"
                        class="w-100"
                        :sort-by="sortBy"
                        multi-sort
                        hover
                        striped="odd"
                    >
                        <template v-slot:item.fecha="{ item }">
                            <div class="d-flex ga-2">
                                {{ date.format(item.fecha, 'keyboardDate') }}
                            </div>
                        </template>
                        <template v-slot:item.actions="{ item }">
                            <div class="d-flex ga-2 justify-end">
                                <!--<v-icon color="primary" icon="mdi-pencil" size="small" @click="edit(item.id)"></v-icon>

                                <v-icon color="error" icon="mdi-delete" size="small" @click="remove(item.id)"></v-icon>
                            -->
                                <v-icon
                                    color="green-darken-2"
                                    icon="mdi-chevron-right-circle-outline"
                                    size="large"
                                    @click="
                                        selectedAction = '';
                                        selectItem(item);
                                    "
                                ></v-icon>
                            </div>
                        </template>
                    </v-data-table>
                </v-card>
            </v-window-item>

            <v-window-item :value="2">
                <v-card class="align-center justify-center">
                    <v-card-title>
                        <h2 class="text-blue-darken-3">{{ selectedItemLabel }}</h2></v-card-title
                    >
                    <v-row dense>
                        <v-col cols="12" md="12">
                            <span class="text-h6">
                                <br />
                                <span>{{ $t('_elija_accion_realizar_') }}</span>
                            </span>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card
                                class="mx-auto"
                                :subtitle="$t('_editar_datos_registro_seleccionado_')"
                                :title="$t('_editar_')"
                                @click="selectAction('edit')"
                            >
                                <template v-slot:prepend>
                                    <v-avatar color="blue-darken-2">
                                        <v-icon icon="mdi-text-box-edit" size="x-large"></v-icon>
                                    </v-avatar>
                                </template>
                                <template v-slot:append>
                                    <v-icon color="success" icon="mdi-check"></v-icon>
                                </template>
                            </v-card>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-card class="mx-auto" :subtitle="$t('_mostrar_datos_solo_lectura_')" :title="$t('_ver_')" @click="selectAction('show')">
                                <template v-slot:prepend>
                                    <v-avatar color="teal-lighten-4">
                                        <v-icon icon="mdi-eye-outline" size="x-large"></v-icon>
                                    </v-avatar>
                                </template>
                                <template v-slot:append>
                                    <v-icon color="success" icon="mdi-check"></v-icon>
                                </template>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card class="mx-auto" subtitle="Borrar el registro seleccionado" title="Eliminar" @click="selectAction('delete')">
                                <template v-slot:prepend>
                                    <v-avatar color="red-lighten-1">
                                        <v-icon icon="mdi-delete-alert" size="x-large"></v-icon>
                                    </v-avatar>
                                </template>
                                <template v-slot:append>
                                    <v-icon color="success" icon="mdi-check"></v-icon>
                                </template>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-card>
            </v-window-item>

            <v-window-item :value="3">
                <ConvocatoriaForm
                    v-if="selectedAction == 'new' || selectedAction == 'edit'"
                    :item="selectedItem"
                    :accion="selectedAction"
                    @form-saved="handleFormSave"
                ></ConvocatoriaForm>
                <ConvocatoriaShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></ConvocatoriaShow>
            </v-window-item>
        </v-window>

        <v-divider></v-divider>

        <v-card-actions>
            <v-btn
                v-if="step > 1 && selectedAction != 'new'"
                prepend-icon="mdi-arrow-left-bold"
                rounded
                variant="tonal"
                color="blue-darken-4"
                @click="step--"
            >
                <template v-slot:prepend>
                    <v-icon color="success"></v-icon>
                </template>
                Regresar
            </v-btn>
            <v-btn v-if="step == 3" prepend-icon="mdi-page-first" rounded variant="tonal" color="blue-darken-4" @click="step = 1">
                <template v-slot:prepend>
                    <v-icon color="success"></v-icon>
                </template>
                Regresar al listado
            </v-btn>
        </v-card-actions>
    </AppLayout>
</template>
