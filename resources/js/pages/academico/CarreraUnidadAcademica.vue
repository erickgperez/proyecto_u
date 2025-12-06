<script setup lang="ts">
import CarreraUnidadAcademicaForm from '@/components/academico/CarreraUnidadAcademicaForm.vue';
import CarreraUnidadAcademicaShow from '@/components/academico/CarreraUnidadAcademicaShow.vue';
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
    semestre: number | null;
    obligatoria: boolean;
    requisito_creditos: number;
    area_id: number | null;
    unidad_academica_id: number | null;
    carrera_id: number | null;
}
const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    areas: Array,
    unidadesAcademicas: Array,
    carreras: Array,
    tiposRequisitos: Array,
});

const itemVacio = ref<Item>({
    id: null,
    semestre: null,
    obligatoria: true,
    requisito_creditos: 0,
    area_id: null,
    unidad_academica_id: null,
    carrera_id: null,
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() =>
    selectedItem.value ? selectedItem.value.carrera.nombreCompleto + ' - ' + selectedItem.value.unidad_academica.nombre : '',
);
const rutaBorrar = ref('academico-plan_estudio-malla_curricular-delete');
const mensajes = {
    titulo1: t('mallaCurricular._plural_'),
    titulo2: t('mallaCurricular._administrar_'),
    subtitulo: t('mallaCurricular._permite_gestionar_'),
    tituloListado: t('mallaCurricular._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_EDITAR',
    mostrar: 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_MOSTRAR',
    borrar: 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_BORRAR',
};
const permisoAny = 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR',
    crear: 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_CREAR',
    exportar: 'ACADEMICO_PLAN-ESTUDIO_MALLA-CURRICULAR_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_malla_curricular');
const fileName = ref('malla_curricular');

const headers = [
    { title: t('carrera._singular_'), key: 'carrera.nombreCompleto' },
    { title: t('mallaCurricular._semestre_'), key: 'semestre' },
    { title: t('unidadAcademica._singular_'), key: 'unidad_academica.nombreCompleto' },
    //{ title: t('area._singular_'), key: 'area.descripcion' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [];
const groupBy = ref([{ key: 'carrera.nombreCompleto', order: 'asc', title: t('carrera._singular_') }]);

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
    <AppLayout :titulo="mensajes.titulo2" :subtitulo="mensajes.subtitulo" icono="mdi-select-group">
        <v-sheet v-if="hasPermission(permisos.listado)" class="elevation-12 rounded-xl">
            <v-window v-model="step" class="h-auto w-100">
                <!-- ************************** CRUD PARTE 1: LISTADO *****************************-->
                <v-window-item :value="1">
                    <Listado
                        @action="handleAction"
                        @selectItem="selectItem"
                        :items="localItems"
                        :headers="headers"
                        :sortBy="sortBy"
                        :groupBy="groupBy"
                        :titleList="mensajes.tituloListado"
                        :permisoCrear="permisos.crear"
                        :permisoExportar="permisos.exportar"
                        :sheetName="sheetName"
                        :fileName="fileName"
                    >
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
                        :selectedItemId="selectedItem.uuid"
                    ></Acciones>
                    <v-alert v-else border="top" type="warning" variant="outlined" prominent>
                        {{ $t('_no_tiene_permiso_para_realizar_ninguna_accion_') }}
                    </v-alert>
                </v-window-item>

                <!-- *********************** CRUD PARTE 3: EJECUTAR ACCIONES ******************************-->
                <v-window-item :value="3">
                    <v-sheet v-if="step === 3" class="bg-transparent">
                        <CarreraUnidadAcademicaForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :unidadesAcademicas="props.unidadesAcademicas"
                            :items="localItems"
                            :areas="props.areas"
                            :carreras="props.carreras"
                            :tiposRequisitos="props.tiposRequisitos"
                            :accion="selectedAction"
                            @form-saved="handleFormSave"
                        ></CarreraUnidadAcademicaForm>
                        <CarreraUnidadAcademicaShow
                            v-if="selectedAction == 'show'"
                            :item="selectedItem"
                            :accion="selectedAction"
                        ></CarreraUnidadAcademicaShow>
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
