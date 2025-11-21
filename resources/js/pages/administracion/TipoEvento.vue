<script setup lang="ts">
import TipoEventoForm from '@/components/administracion/TipoEventoForm.vue';
import TipoEventoShow from '@/components/administracion/TipoEventoShow.vue';
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
    descripcion: string;
    tipo_calendarizacion: number | null;
}
const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    tiposCalendarizacion: Array,
});

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    descripcion: '',
    tipo_calendarizacion: null,
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() => selectedItem.value?.codigo ?? '');
const rutaBorrar = ref('calendarizacion-tipo_evento-delete');
const mensajes = {
    titulo1: t('tipoEvento._plural_'),
    titulo2: t('tipoEvento._administrar_'),
    subtitulo: t('tipoEvento._permite_gestionar_'),
    tituloListado: t('tipoEvento._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_EDITAR',
    mostrar: 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_MOSTRAR',
    borrar: 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_BORRAR',
};
const permisoAny = 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO',
    crear: 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_CREAR',
    exportar: 'ADMINISTRACION_CALENDARIZACION_TIPO-EVENTO_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_tipos_eventos');
const fileName = ref('tipos_eventos');

const headers = [
    { title: t('_codigo_'), key: 'codigo' },
    { title: t('_descripcion_'), key: 'descripcion' },
    { title: t('tipoCalendarizacion._singular_'), key: 'tipo_calendarizacion.codigo' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [{ key: 'tipo_calendarizacion.codigo', order: 'asc' }];

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
    <AppLayout :titulo="mensajes.titulo2" :subtitulo="mensajes.subtitulo" icono="mdi-calendar-week-outline">
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
                        v-if="hasPermission(permisoAny) && selectedItem.id !== null"
                        :acciones="opcionesAccion"
                        :selectedItemLabel="selectedItemLabel"
                        :rutaBorrar="rutaBorrar"
                        :selectedItemId="selectedItem.uuid"
                    ></Acciones>
                    <v-alert v-else border="top" type="warning" variant="outlined" prominent>
                        {{ $t('_no_tiene_permiso_para_realizar_ninguna_accion_') }}
                    </v-alert>
                </v-window-item>

                <!-- *********************** CRUD PARTE 3: EJECUTAR ACCIONES ******************************-->
                <v-window-item :value="3">
                    <v-sheet v-if="step === 3">
                        <TipoEventoForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :accion="selectedAction"
                            :tipos-calendarizacion="props.tiposCalendarizacion"
                            @form-saved="handleFormSave"
                        ></TipoEventoForm>
                        <TipoEventoShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></TipoEventoShow>
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
