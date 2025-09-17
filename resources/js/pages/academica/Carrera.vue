<script setup lang="ts">
import CarreraForm from '@/components/academica/CarreraForm.vue';
import CarreraShow from '@/components/academica/CarreraShow.vue';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { saveAs } from 'file-saver';
import Swal from 'sweetalert2';
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
const rutaBorrar = ref('plan_estudio-carrera-delete');
const mensajes = {
    titulo1: t('carrera._plural_'),
    titulo2: t('carrera._administrar_'),
    subtitulo: t('carrera._permite_gestionar_'),
    tituloListado: t('carrera._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ACADEMICA_PLAN-ESTUDIO_CARRERA_EDITAR',
    mostrar: 'ACADEMICA_PLAN-ESTUDIO_CARRERA_MOSTRAR',
    borrar: 'ACADEMICA_PLAN-ESTUDIO_CARRERA_BORRAR',
};
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ACADEMICA_PLAN-ESTUDIO_CARRERA',
    crear: 'ACADEMICA_PLAN-ESTUDIO_CARRERA_CREAR',
    exportar: 'ACADEMICA_PLAN-ESTUDIO_CARRERA_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

interface Sede {
    id: number | null;
    codigo: string;
    nombre: string;
}

interface TipoCarrera {
    id: number | null;
    codigo: string;
    descripcion: string;
}

interface Carrera {
    id: number | null;
    codigo: string;
    descripcion: string;
}

interface Item {
    id: number | null;
    codigo: string;
    nombre: string;
    padre: Carrera | null;
    tipoCarrera: TipoCarrera | null;
}

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    nombre: '',
    padre: null,
    tipoCarrera: null,
});

const selectedItem = ref<Item>(itemVacio.value);

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_carreras');
const fileName = ref('carreras');

const selectedItemLabel = computed(() => selectedItem.value?.nombre ?? '');

// Título del listado
const titleList = ref(mensajes.tituloListado);

const headers = [
    { title: t('_codigo_'), key: 'codigo' },
    { title: t('_nombre_'), key: 'nombre' },
    { title: t('carrera._padre_'), key: 'padre.nombreCompleto' },
    { title: t('carrera._tipo_'), key: 'tipo.descripcion' },
    { title: t('_acciones_'), key: 'actions', align: 'end' },
];

const sortBy = [
    { key: 'tipo.descripcion', order: 'desc' },
    { key: 'padre.nombre', order: 'asc' },
    { key: 'nombre', order: 'asc' },
];

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    tiposCarrera: {
        type: Array as PropType<TipoCarrera[]>,
        required: true,
        default: () => [],
    },
    sedes: {
        type: Array as PropType<Sede[]>,
        required: true,
        default: () => [],
    },
});

//************ lo demás puede permanecer igual, cambiar solo que sea necesario

const localItems = ref([...props.items]);

function remove() {
    const hasError = ref(false);
    const message = ref('');
    const messageLog = ref('');
    Swal.fire({
        title: t('_confirmar_borrar_registro_'),
        text: selectedItemLabel.value,
        showCancelButton: true,
        confirmButtonText: t('_borrar_'),
        cancelButtonText: t('_cancelar_'),
        confirmButtonColor: '#e5adac',
        cancelButtonColor: '#D7E1EE',
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const resp = await axios.delete(route(rutaBorrar.value, { id: selectedItem.value?.id }));
                if (resp.data.status == 'ok') {
                    Swal.fire({
                        title: t('_exito_'),
                        text: t('_registro_eliminado_correctamente_'),
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        toast: true,
                    });

                    const index = localItems.value.findIndex((item) => item.id === selectedItem.value?.id);
                    localItems.value.splice(index, 1);

                    step.value = 1;
                } else {
                    hasError.value = true;
                    message.value = t(resp.data.message);
                }
            } catch (error: any) {
                hasError.value = true;
                messageLog.value = error.response.data.message;
            }

            if (hasError.value) {
                console.log(messageLog.value);
                Swal.fire({
                    title: t('_error_'),
                    text: t('_no_se_pudo_eliminar_') + '. ' + message.value,
                    icon: 'error',
                    confirmButtonColor: '#D7E1EE',
                });
            }
        }
    });
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
                    <v-card v-if="hasAnyPermission(permisos.acciones)" class="align-center justify-center">
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
                            <v-col v-if="hasPermission(permisos.editar)" cols="12" md="6">
                                <v-card
                                    class="mx-auto"
                                    :subtitle="$t('_editar_datos_registro_seleccionado_')"
                                    :title="$t('_editar_')"
                                    @click="
                                        selectAction('edit');
                                        step++;
                                    "
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

                            <v-col v-if="hasPermission(permisos.mostrar)" cols="12" md="6">
                                <v-card
                                    class="mx-auto"
                                    :subtitle="$t('_mostrar_datos_solo_lectura_')"
                                    :title="$t('_ver_')"
                                    @click="
                                        selectAction('show');
                                        step++;
                                    "
                                >
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
                            <v-col v-if="hasPermission(permisos.borrar)" cols="12" md="6">
                                <v-card
                                    class="mx-auto"
                                    :subtitle="$t('_borrar_registro_seleccionado_')"
                                    :title="$t('_eliminar_')"
                                    @click="
                                        selectAction('delete');
                                        remove();
                                    "
                                >
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
                    <v-alert v-else border="top" type="warning" variant="outlined" prominent>
                        {{ $t('_no_tiene_permiso_para_realizar_ninguna_accion_') }}
                    </v-alert>
                </v-window-item>

                <!-- *********************** CRUD PARTE 3: EJECUTAR ACCIONES ******************************-->
                <v-window-item :value="3">
                    <CarreraForm
                        v-if="selectedAction == 'new' || selectedAction == 'edit'"
                        :item="selectedItem"
                        :carreras="props.items"
                        :tiposCarrera="props.tiposCarrera"
                        :sedes="props.sedes"
                        :accion="selectedAction"
                        @form-saved="handleFormSave"
                    ></CarreraForm>
                    <CarreraShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></CarreraShow>
                </v-window-item>
            </v-window>

            <v-divider></v-divider>

            <!-- ************************* NAVEGACIÓN ENTRE LAS PARTES DEL CRUD ****************************-->
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
                    {{ $t('_atras_') }}
                </v-btn>
                <v-btn v-if="step == 3" prepend-icon="mdi-page-first" rounded variant="tonal" color="blue-darken-4" @click="step = 1">
                    <template v-slot:prepend>
                        <v-icon color="success"></v-icon>
                    </template>
                    {{ $t('_regresar_listado_') }}
                </v-btn>
            </v-card-actions>
        </v-sheet>
        <v-alert v-else border="top" type="warning" variant="outlined" prominent>
            {{ $t('_no_tiene_permiso_para_esta_accion_') }}
        </v-alert>
    </AppLayout>
</template>
