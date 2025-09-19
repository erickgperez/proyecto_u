<script setup lang="ts">
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import Evento from '@/components/Evento.vue';
import ConvocatoriaForm from '@/components/ingreso/ConvocatoriaForm.vue';
import ConvocatoriaShow from '@/components/ingreso/ConvocatoriaShow.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { saveAs } from 'file-saver';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';
import * as XLSX from 'xlsx';

const { hasPermission, hasAnyPermission } = usePermissions();

const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();

const step = ref(1);

const { t } = useI18n();

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

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
const rutaBorrar = ref('ingreso-convocatoria-delete');
const mensajes = {
    titulo1: t('convocatoria._convocatoria_'),
    titulo2: t('_administrar_convocatoria_'),
    subtitulo: t('_permite_gestionar_datos_convocatorias_'),
    tituloListado: t('_listado_convocatorias_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'INGRESO_CONVOCATORIA_EDITAR',
    mostrar: 'INGRESO_CONVOCATORIA_MOSTRAR',
    borrar: 'INGRESO_CONVOCATORIA_BORRAR',
    calendarizar: 'INGRESO_CONVOCATORIA_CALENDARIZAR',
};

// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_INGRESO_CONVOCATORIA_GESTIONAR',
    crear: 'INGRESO_CONVOCATORIA_CREAR',
    exportar: 'INGRESO_CONVOCATORIA_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

interface Item {
    id: number | null;
    fecha: Date | null;
    nombre: string;
    descripcion: string;
    cuerpo_mensaje: string;
    afiche: string | null;
    calendarizacion_id: number | null;
}
const itemVacio = ref<Item>({
    id: null,
    fecha: null,
    nombre: '',
    descripcion: '',
    cuerpo_mensaje: '',
    afiche: null,
    calendarizacion_id: null,
});

const selectedItem = ref<Item>(itemVacio.value);

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_convocatorias');
const fileName = ref('convocatorias');

const selectedItemLabel = computed(() => selectedItem.value?.nombre ?? '');

// Título del listado
const titleList = ref(mensajes.tituloListado);

const headers = [
    { title: t('_fecha_'), key: 'fecha' },
    { title: t('_nombre_'), key: 'nombre', align: 'start' },
    { title: t('_descripcion_'), key: 'descripcion' },
    { title: t('_acciones_'), key: 'actions', align: 'end' },
];

const sortBy = [
    { key: 'fecha', order: 'asc' },
    { key: 'nombre', order: 'asc' },
];

const opcionesAccion = [
    {
        permiso: acc.calendarizar,
        title: t('convocatoria._calendarizar_'),
        text: t('convocatoria._calendarizar_descripcion_'),
        emitAction: 'calendarizar',
        avatarColor: 'brown',
        icon: 'mdi-calendar-month-outline',
    },
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
const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
});

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
    <Head :title="$t('convocatoria._convocatoria_')"> </Head>
    <AppLayout :titulo="$t('_administrar_convocatoria_')" :subtitulo="$t('_permite_gestionar_datos_convocatorias_')" icono="mdi-wrench-clock">
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

                <!-- ********************* CRUD PARTE 2: ELEGIR ACCION A REALIZAR ****************************-->
                <v-window-item :value="2" class="pa-5">
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
                    <ConvocatoriaForm
                        v-if="selectedAction == 'new' || selectedAction == 'edit'"
                        :item="selectedItem"
                        :accion="selectedAction"
                        @form-saved="handleFormSave"
                    ></ConvocatoriaForm>
                    <ConvocatoriaShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></ConvocatoriaShow>
                    <Evento v-if="selectedAction == 'calendarizar'" :idCalendario="selectedItem.calendarizacion_id"></Evento>
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
