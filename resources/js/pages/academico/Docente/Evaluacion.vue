<script setup lang="ts">
import EvaluacionForm from '@/components/academico/docente/EvaluacionForm.vue';
import EvaluacionShow from '@/components/academico/docente/EvaluacionShow.vue';
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
import { useDate } from 'vuetify';

const { hasPermission } = usePermissions();
const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();
const { t } = useI18n();
const date = useDate();

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
interface Item {
    id: number | null;
    codigo: string;
    descripcion: string;
    fecha: Date | null;
    fecha_limite_ingreso_nota: Date | null;
    porcentaje: number | null;
    oferta_id: number | null;
}
const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    oferta: Object,
});

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    descripcion: '',
    fecha: null,
    fecha_limite_ingreso_nota: null,
    porcentaje: null,
    oferta_id: props.oferta?.id,
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() => selectedItem.value?.codigo ?? '');
const rutaBorrar = ref('academico-evaluacion-delete');
const mensajes = {
    titulo1: t('evaluacion._plural_'),
    titulo2: t('evaluacion._administrar_'),
    subtitulo: t('evaluacion._permite_gestionar_'),
    tituloListado: t('evaluacion._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ACADEMICO_EVALUACION_EDITAR',
    mostrar: 'ACADEMICO_EVALUACION_MOSTRAR',
    borrar: 'ACADEMICO_EVALUACION_BORRAR',
};
const permisoAny = 'ACADEMICO_EVALUACION_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ACADEMICO_EVALUACION',
    crear: 'ACADEMICO_EVALUACION_CREAR',
    exportar: 'ACADEMICO_EVALUACION_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_evaluaciones');
const fileName = ref('evaluaciones');

const headers = [
    { title: t('_codigo_'), key: 'codigo' },
    { title: t('_descripcion_'), key: 'descripcion' },
    { title: t('evaluacion._porcentaje_'), key: 'porcentaje' },
    { title: t('evaluacion._fecha_'), key: 'fecha', align: 'center' },
    { title: t('evaluacion._fecha_limite_ingreso_nota_'), key: 'fecha_limite_ingreso_nota', align: 'center' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [{ key: 'codigo', order: 'asc' }];

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
    <AppLayout
        :titulo="oferta.carrera_unidad_academica.unidad_academica.nombre"
        :subtitulo="$t('semestre._singular_') + ' ' + oferta.semestre.nombre"
        icono="mdi-book-open-variant-outline"
    >
        <v-sheet v-if="props.oferta" class="elevation-12 pa-2 rounded-xl">
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
                        :permitirCrear="true"
                    >
                        <template v-slot:item.fecha="{ value }">
                            <div class="d-flex ga-2 justify-center">
                                {{ value !== null ? date.format(value, 'keyboardDate') : '' }}
                            </div>
                        </template>
                        <template v-slot:item.fecha_limite_ingreso_nota="{ value }">
                            <div class="d-flex ga-2 justify-center">
                                {{ value !== null ? date.format(value, 'keyboardDate') : '' }}
                            </div>
                        </template>
                        <template v-slot:item.porcentaje="{ value }">
                            <div class="d-flex ga-2 justify-end">{{ value !== null ? value : '' }}%</div>
                        </template>
                    </Listado>
                </v-window-item>

                <!-- ********************* CRUD PARTE 2: ELEGIR ACCION A REALIZAR ****************************-->
                <v-window-item :value="2">
                    <Acciones
                        @action="handleAction"
                        v-if="selectedItem.id !== null"
                        :acciones="opcionesAccion"
                        :selectedItemLabel="selectedItemLabel"
                        :rutaBorrar="rutaBorrar"
                        :selectedItemId="selectedItem.uuid"
                        :permitirAcciones="true"
                    ></Acciones>
                    <v-alert v-else border="top" type="warning" variant="outlined" prominent>
                        {{ $t('_no_tiene_permiso_para_realizar_ninguna_accion_') }}
                    </v-alert>
                </v-window-item>

                <!-- *********************** CRUD PARTE 3: EJECUTAR ACCIONES ******************************-->
                <v-window-item :value="3">
                    <v-sheet v-if="step === 3">
                        <EvaluacionForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :accion="selectedAction"
                            :oferta="oferta"
                            @form-saved="handleFormSave"
                        ></EvaluacionForm>
                        <EvaluacionShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></EvaluacionShow>
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
