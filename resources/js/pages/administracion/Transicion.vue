<script setup lang="ts">
import TransicionForm from '@/components/administracion/TransicionForm.vue';
import TransicionShow from '@/components/administracion/TransicionShow.vue';
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import Listado from '@/components/crud/Listado.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { useFuncionesCrud } from '@/composables/useFuncionesCrud';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import type { SortBy } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();
const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();
const { t } = useI18n();

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
interface Item {
    id: number | null;
    codigo: string;
    nombre: string;
    flujo_id: number | null;
    etapa_origen_id: number | null;
    etapa_destino_id: number | null;
    estado_origen_id: number | null;
    estado_destino_id: number | null;
}

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    flujos: Array,
    etapas: Array,
    estados: Array,
});
const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    nombre: '',
    flujo_id: null,
    etapa_origen_id: null,
    etapa_destino_id: null,
    estado_origen_id: null,
    estado_destino_id: null,
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() => selectedItem.value?.nombre ?? '');
const rutaBorrar = ref('proceso-transicion-delete');
const mensajes = {
    titulo1: t('transicion._singular_'),
    titulo2: t('transicion._administrar_'),
    subtitulo: t('transicion._permite_gestionar_datos_'),
    tituloListado: t('transicion._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ADMINISTRACION_PROCESO_TRANSICION_EDITAR',
    mostrar: 'ADMINISTRACION_PROCESO_TRANSICION_MOSTRAR',
    borrar: 'ADMINISTRACION_PROCESO_TRANSICION_BORRAR',
};
const permisoAny = 'ADMINISTRACION_PROCESO_TRANSICION_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ADMINISTRACION_PROCESO_TRANSICION',
    crear: 'ADMINISTRACION_PROCESO_TRANSICION_CREAR',
    exportar: 'ADMINISTRACION_PROCESO_TRANSICION_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_transiciones');
const fileName = ref('transiciones');

const headers = [
    { title: t('_id_'), key: 'id' },
    { title: t('transicion._codigo_'), key: 'codigo' },
    { title: t('transicion._nombre_'), key: 'nombre' },
    { title: t('flujo._singular_'), key: 'flujo' },
    { title: t('transicion._origen_'), key: 'etapa_origen' },
    { title: t('transicion._destino_'), key: 'etapa_destino' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [
    { key: 'flujo', order: 'asc' },
    { key: 'id', order: 'asc' },
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
                    >
                        <template v-slot:item.flujo="{ value }">
                            <div class="d-flex ga-2">
                                {{ value.codigo }}
                            </div>
                        </template>
                        <template v-slot:item.etapa_origen="{ value, item }">
                            <div class="d-flex ga-2">{{ value.codigo }} ({{ item.estado_origen.codigo }})</div>
                        </template>

                        <template v-slot:item.etapa_destino="{ value, item }">
                            <div class="d-flex ga-2">{{ value.codigo }} ({{ item.estado_destino.codigo }})</div>
                        </template>
                    </Listado>
                </v-window-item>
                <!-- ********************* CRUD PARTE 2: ELEGIR ACCION A REALIZAR ****************************-->
                <v-window-item :value="2">
                    <Acciones
                        @action="handleAction"
                        v-if="hasPermission(permisoAny) && selectedItem.id !== null"
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
                        <TransicionForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :accion="selectedAction"
                            :flujos="flujos"
                            :etapas="etapas"
                            :estados="estados"
                            @form-saved="handleFormSave"
                        ></TransicionForm>
                        <TransicionShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></TransicionShow>
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
