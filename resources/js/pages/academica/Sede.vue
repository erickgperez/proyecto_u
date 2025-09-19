<script setup lang="ts">
import SedeForm from '@/components/academica/SedeForm.vue';
import SedeShow from '@/components/academica/SedeShow.vue';
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { saveAs } from 'file-saver';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import * as XLSX from 'xlsx';

const { hasPermission, hasAnyPermission } = usePermissions();

const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();

const step = ref(1);

const { t } = useI18n();

const selectedAction = ref('');

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

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
const rutaBorrar = ref('academica-sede-delete');
const mensajes = {
    titulo1: t('sede._sedes_'),
    titulo2: t('sede._administrar_sedes_'),
    subtitulo: t('sede._permite_gestionar_sedes_'),
    tituloListado: t('sede._listado_sedes_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ACADEMICA_SEDE_EDITAR',
    mostrar: 'ACADEMICA_SEDE_MOSTRAR',
    borrar: 'ACADEMICA_SEDE_BORRAR',
};
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ACADEMICA_SEDES',
    crear: 'ACADEMICA_SEDE_CREAR',
    exportar: 'ACADEMICA_SEDE_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

interface Distrito {
    id: number | null;
    descripcion: string;
    municipio_id: number | null;
}

interface Departamento {
    id: number | null;
    descripcion: string;
}

interface Municipio {
    id: number | null;
    descripcion: string;
    departamento_id: number | null;
}

interface Item {
    id: number | null;
    codigo: string;
    nombre: string;
    distrito: Distrito | null;
    municipio_id: number | null;
    departamento_id: number | null;
}

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    nombre: '',
    distrito: null,
    municipio_id: null,
    departamento_id: null,
});

const selectedItem = ref<Item>(itemVacio.value);

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_sedes');
const fileName = ref('sedes');

const selectedItemLabel = computed(() => selectedItem.value?.nombre ?? '');

// Título del listado
const titleList = ref(mensajes.tituloListado);

const headers = [
    { title: t('_codigo_'), key: 'codigo' },
    { title: t('_nombre_'), key: 'nombre', align: 'start' },
    { title: t('_distrito_'), key: 'distrito' },
    { title: t('_acciones_'), key: 'actions', align: 'end' },
];

const sortBy = [{ key: 'nombre', order: 'asc' }];

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    distritos: {
        type: Array as PropType<Distrito[]>,
        required: true,
        default: () => [],
    },
    departamentos: {
        type: Array as PropType<Departamento[]>,
        required: true,
        default: () => [],
    },
    municipios: {
        type: Array as PropType<Municipio[]>,
        required: true,
        default: () => [],
    },
});

const opcionesAccion = [
    {
        permiso: acc.editar,
        ...accionEditObject,
    },
    {
        permiso: acc.mostrar,
        ...accionShowObject,
    },
    {
        permiso: acc.borrar,
        ...accionDeleteObject,
    },
];

const handleAction = (action: string) => {
    selectAction(action);
    if (action === 'delete') {
        remove();
    } else {
        step.value++;
    }
};

const handleNextStep = (stepValue: number) => {
    step.value = stepValue;
};

//************ lo demás puede permanecer igual, cambiar solo que sea necesario

const localItems = ref([...props.items]);

function remove() {
    const index = localItems.value.findIndex((item) => item.id === selectedItem.value?.id);
    localItems.value.splice(index, 1);

    step.value = 1;
}

const selectItem = (item: any) => {
    selectedItem.value = item;

    step.value++;
};

const selectAction = (accion: string) => {
    selectedAction.value = accion;
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
    <Head :title="mensajes.titulo1"> </Head>
    <AppLayout :titulo="mensajes.titulo2" :subtitulo="mensajes.subtitulo" icono="mdi-wrench-clock">
        <v-sheet v-if="hasPermission(permisos.listado)">
            <v-window v-model="step" class="h-auto w-100">
                <!-- ************************** CRUD PARTE 1: LISTADO *****************************-->
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
                                v-if="hasPermission(permisos.crear)"
                                icon="mdi-table-plus"
                                color="success"
                                class="ml-2"
                                :title="$t('_crear_nuevo_registro_')"
                                @click="
                                    selectAction('new');
                                    selectedItem = itemVacio;
                                    step = 3;
                                "
                            ></v-btn>
                            <v-btn
                                v-if="hasPermission(permisos.exportar)"
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

                <!-- ********************* CRUD PARTE 2: ELEGIR ACCION A REALIZAR ****************************-->
                <v-window-item :value="2">
                    <Acciones
                        @action="handleAction"
                        v-if="hasAnyPermission(permisos.acciones) && selectedItem.id !== null"
                        :acciones="opcionesAccion"
                        :selectedItemLabel="selectedItemLabel"
                        :rutaBorrar="rutaBorrar"
                        :selectedItemId="selectedItem.id"
                    ></Acciones>
                    <v-alert v-else border="top" type="warning" variant="outlined" prominent>
                        {{ $t('_no_tiene_permiso_para_realizar_ninguna_accion_') }}
                    </v-alert>
                </v-window-item>

                <!-- *********************** CRUD PARTE 3: EJECUTAR ACCIONES ******************************-->
                <v-window-item :value="3">
                    <SedeForm
                        v-if="selectedAction == 'new' || selectedAction == 'edit'"
                        :item="selectedItem"
                        :distritos="props.distritos"
                        :departamentos="props.departamentos"
                        :municipios="props.municipios"
                        :accion="selectedAction"
                        @form-saved="handleFormSave"
                    ></SedeForm>
                    <SedeShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></SedeShow>
                </v-window-item>
            </v-window>

            <v-divider></v-divider>

            <!-- ************************* NAVEGACIÓN ENTRE LAS PARTES DEL CRUD ****************************-->
            <v-card-actions>
                <BotonesNavegacion :selectedAction="selectedAction" :step="step" @nextStep="handleNextStep"> </BotonesNavegacion>
            </v-card-actions>
        </v-sheet>
        <v-alert v-else border="top" type="warning" variant="outlined" prominent>
            {{ $t('_no_tiene_permiso_para_esta_accion_') }}
        </v-alert>
    </AppLayout>
</template>
