<script setup lang="ts">
import TipoCarreraForm from '@/components/academica/TipoCarreraForm.vue';
import TipoCarreraShow from '@/components/academica/TipoCarreraShow.vue';
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import Listado from '@/components/crud/Listado.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { useFuncionesCrud } from '@/composables/useFuncionesCrud';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Grado, SortBy } from '@/types/crud';
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
    descripcion: string;
    grado: Grado | null;
}

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

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    descripcion: '',
    grado: null,
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() => selectedItem.value?.descripcion ?? '');
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

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_tipos_carreras');
const fileName = ref('tipos_carrera');

const headers = [
    { title: t('_codigo_'), key: 'codigo' },
    { title: t('_descripcion_'), key: 'descripcion', align: 'start' },
    { title: t('grado._grado_'), key: 'grado.descripciones' },
    { title: t('_acciones_'), key: 'actions', align: 'end' },
];

const sortBy: SortBy[] = [{ key: 'nombre', order: 'asc' }];

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
