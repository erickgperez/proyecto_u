<script setup lang="ts">
import CarreraForm from '@/components/academica/CarreraForm.vue';
import CarreraShow from '@/components/academica/CarreraShow.vue';
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import Listado from '@/components/crud/Listado.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { useFuncionesCrud } from '@/composables/useFuncionesCrud';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Carrera, Sede, SortBy, TipoCarrera } from '@/types/crud';
import { Head } from '@inertiajs/vue3';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission, hasAnyPermission } = usePermissions();
const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();
const { t } = useI18n();

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************

interface Item {
    id: number | null;
    codigo: string;
    nombre: string;
    padre: Carrera | null;
    tipoCarrera: TipoCarrera | null;
}

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

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    nombre: '',
    padre: null,
    tipoCarrera: null,
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() => selectedItem.value?.nombre ?? '');

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

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_carreras');
const fileName = ref('carreras');

const headers = [
    { title: t('_codigo_'), key: 'codigo' },
    { title: t('_nombre_'), key: 'nombre' },
    { title: t('carrera._padre_'), key: 'padre.nombreCompleto' },
    { title: t('carrera._tipo_'), key: 'tipo.descripcion' },
    { title: t('_acciones_'), key: 'actions', align: 'end' },
];

const sortBy: SortBy[] = [
    { key: 'tipo.descripcion', order: 'desc' },
    { key: 'padre.nombre', order: 'asc' },
    { key: 'nombre', order: 'asc' },
];

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
</script>

<template>
    <!--********************************************************************************
    ******** LA PLANTILLA GENERALMENTE NO SERÁ NECESARIO MODIFICAR
    ******** Solo para agregar/cambiar un elemento en la parte 3: Ejecutar acciones, o modificar la
    ******** presentación de un campo en el listado de la parte 1
    ************************************************************************************
    -->
    <Head :title="mensajes.titulo1"> </Head>
    <AppLayout :titulo="mensajes.titulo2" :subtitulo="mensajes.subtitulo" icono="mdi-wrench-clock">
        <v-sheet v-if="hasPermission(permisos.listado)" class="elevation-12 pa-2 rounded-xl">
            <v-window v-model="step" class="h-auto w-100">
                <!-- ************************** CRUD PARTE 1: LISTADO *****************************-->
                <v-window-item :value="1">
                    <Listado
                        @action="handleAction"
                        @selectItem="selectItem"
                        :items="localItems"
                        :headers="headers"
                        :sortBy="sortBy"
                        :titleList="mensajes.tituloListado"
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
                    <v-sheet v-if="step === 3">
                        <CarreraForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :carreras="props.items"
                            :tiposCarrera="props.tiposCarrera"
                            :sedes="props.sedes"
                            :accion="selectedAction"
                            @form-saved="handleFormSave"
                        ></CarreraForm>
                        <CarreraShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></CarreraShow>
                    </v-sheet>
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
