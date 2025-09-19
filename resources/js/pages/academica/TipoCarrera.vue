<script setup lang="ts">
import TipoCarreraForm from '@/components/academica/TipoCarreraForm.vue';
import TipoCarreraShow from '@/components/academica/TipoCarreraShow.vue';
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { saveAs } from 'file-saver';
import { computed, PropType, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import * as XLSX from 'xlsx';

const { hasPermission, hasAnyPermission } = usePermissions();

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
const rutaBorrar = ref('plan_estudio-tipo_carrera-delete');
const mensajes = {
    titulo1: t('tipoCarrera._tipos_carrera_'),
    titulo2: t('tipoCarrera._administrar_tipo_carrera_'),
    subtitulo: t('tipoCarrera._permite_gestionar_tipos_carrera_'),
    tituloListado: t('tipoCarrera._listado_tipos_carrera_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_EDITAR',
    mostrar: 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_MOSTRAR',
    borrar: 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_BORRAR',
};
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA',
    crear: 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_CREAR',
    exportar: 'ACADEMICA_PLAN-ESTUDIO_TIPO-CARRERA_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

interface Grado {
    id: number | null;
    descripcion_masculino: string;
    descripcion_femenino: string;
}

interface Item {
    id: number | null;
    codigo: string;
    descripcion: string;
    grado: Grado | null;
}

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    descripcion: '',
    grado: null,
});

const selectedItem = ref<Item>(itemVacio.value);

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_tipos_carreras');
const fileName = ref('tipos_carrera');

const selectedItemLabel = computed(() => selectedItem.value?.descripcion ?? '');

// Título del listado
const titleList = ref(mensajes.tituloListado);

const headers = [
    { title: t('_codigo_'), key: 'codigo' },
    { title: t('_descripcion_'), key: 'descripcion', align: 'start' },
    { title: t('grado._grado_'), key: 'grado.descripciones' },
    { title: t('_acciones_'), key: 'actions', align: 'end' },
];

const sortBy = [{ key: 'nombre', order: 'asc' }];

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    grados: {
        type: Array as PropType<Grado[]>,
        required: true,
        default: () => [],
    },
});

const opcionesAccion = [
    {
        permiso: acc.editar,
        title: t('_editar_'),
        text: t('_editar_datos_registro_seleccionado_'),
        emitAction: 'edit',
        avatarColor: 'blue-darken-2',
        icon: 'mdi-text-box-edit',
    },
    {
        permiso: acc.mostrar,
        title: t('_ver_'),
        text: t('_mostrar_datos_solo_lectura_'),
        emitAction: 'show',
        avatarColor: 'teal-lighten-4',
        icon: 'mdi-eye-outline',
    },
    {
        permiso: 'INGRESO_CONVOCATORIA_BORRAR',
        title: t('_eliminar_'),
        text: t('_borrar_registro_seleccionado_'),
        emitAction: 'delete',
        avatarColor: 'red-lighten-1',
        icon: 'mdi-delete-alert',
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

//************ lo demás puede permanecer igual, cambiar solo que sea necesario
const handleNextStep = (stepValue: number) => {
    step.value = stepValue;
};

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

watch(
    () => step.value,
    (newValue) => {
        if (newValue < 3) {
            selectedAction.value = '';
        }
    },
);
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
                    <TipoCarreraForm
                        v-if="selectedAction == 'new' || selectedAction == 'edit'"
                        :item="selectedItem"
                        :grados="props.grados"
                        :accion="selectedAction"
                        @form-saved="handleFormSave"
                    ></TipoCarreraForm>
                    <TipoCarreraShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></TipoCarreraShow>
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
