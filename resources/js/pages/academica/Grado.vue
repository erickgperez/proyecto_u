<script setup lang="ts">
import GradoForm from '@/components/academica/GradoForm.vue';
import GradoShow from '@/components/academica/GradoShow.vue';
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import Listado from '@/components/crud/Listado.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission, hasAnyPermission } = usePermissions();

const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();

const step = ref(1);

const { t } = useI18n();

const selectedAction = ref('');

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
const rutaBorrar = ref('plan_estudio-grado-delete');
const mensajes = {
    titulo1: t('grado._grados_'),
    titulo2: t('grado._administrar_grados_'),
    subtitulo: t('grado._permite_gestionar_grados_'),
    tituloListado: t('grado._listado_grados_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ACADEMICA_PLAN-ESTUDIO_GRADO_EDITAR',
    mostrar: 'ACADEMICA_PLAN-ESTUDIO_GRADO_MOSTRAR',
    borrar: 'ACADEMICA_PLAN-ESTUDIO_GRADO_BORRAR',
};
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ACADEMICA_PLAN-ESTUDIO_GRADO',
    crear: 'ACADEMICA_PLAN-ESTUDIO_GRADO_CREAR',
    exportar: 'ACADEMICA_PLAN-ESTUDIO_GRADO_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

interface Item {
    id: number | null;
    codigo: string;
    descripcion_masculino: string;
    descripcion_femenino: string;
}

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    descripcion_masculino: '',
    descripcion_femenino: '',
});

const selectedItem = ref<Item>(itemVacio.value);

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_grados');
const fileName = ref('grados');

const selectedItemLabel = computed(() => selectedItem.value?.codigo ?? '');

// Título del listado
const titleList = ref(mensajes.tituloListado);

const headers = [
    { title: t('_codigo_'), key: 'codigo' },
    { title: t('grado._descripcion_masculino_'), key: 'descripcion_masculino' },
    { title: t('grado._descripcion_femenino_'), key: 'descripcion_femenino' },
    { title: t('_acciones_'), key: 'actions', align: 'end' },
];

const sortBy = [{ key: 'codigo', order: 'asc' }];

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
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
    } else if (action === 'new') {
        selectItem(itemVacio.value);
        step.value = 3;
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

const selectItem = (item: Item) => {
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
                    <Listado
                        @action="handleAction"
                        @selectItem="selectItem"
                        :items="localItems"
                        :headers="headers"
                        :sortBy="sortBy"
                        :titleList="titleList"
                        :permisoCrear="permisos.crear"
                        :permisoExportar="permisos.exportar"
                        :sheetName="sheetName"
                        :fileName="fileName"
                    ></Listado>
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
                    <GradoForm
                        v-if="selectedAction == 'new' || selectedAction == 'edit'"
                        :item="selectedItem"
                        :accion="selectedAction"
                        @form-saved="handleFormSave"
                    ></GradoForm>
                    <GradoShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></GradoShow>
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
