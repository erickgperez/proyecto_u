<script setup lang="ts">
import SemestreForm from '@/components/academico/SemestreForm.vue';
import SemestreOferta from '@/components/academico/SemestreOferta.vue';
import SemestreShow from '@/components/academico/SemestreShow.vue';
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import Listado from '@/components/crud/Listado.vue';
import Evento from '@/components/Evento.vue';
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
    anio: number | null;
    descripcion: string;
    fecha_inicio: Date | null;
    fecha_fin: Date | null;
}
const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
});

const itemVacio = ref<Item>({
    id: null,
    codigo: '',
    anio: null,
    descripcion: '',
    fecha_inicio: null,
    fecha_fin: null,
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() => selectedItem.value?.nombre ?? '');
const rutaBorrar = ref('academico-semestre-delete');
const mensajes = {
    titulo1: t('semestre._plural_'),
    titulo2: t('semestre._administrar_'),
    subtitulo: t('semestre._permite_gestionar_'),
    tituloListado: t('semestre._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ACADEMICO_SEMESTRE_EDITAR',
    mostrar: 'ACADEMICO_SEMESTRE_MOSTRAR',
    borrar: 'ACADEMICO_SEMESTRE_BORRAR',
    oferta: 'ACADEMICO_SEMESTRE_OFERTA',
    calendarizar: 'ACADEMICO_SEMESTRE_CALENDARIZAR',
};
const permisoAny = 'ACADEMICO_SEMESTRE_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ACADEMICO_SEMESTRE',
    crear: 'ACADEMICO_SEMESTRE_CREAR',
    exportar: 'ACADEMICO_SEMESTRE_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_semestres');
const fileName = ref('semestres');

const headers = [
    { title: t('_identificador_'), key: 'nombre' },
    { title: t('_descripcion_'), key: 'descripcion' },
    {
        title: t('_fecha_inicio_'),
        key: 'fecha_inicio',
        value: (item) => (item.fecha_inicio ? date.format(item.fecha_inicio, 'keyboardDate') : ''),
    },
    {
        title: t('_fecha_fin_'),
        key: 'fecha_fin',
        value: (item) => (item.fecha_fin ? date.format(item.fecha_fin, 'keyboardDate') : ''),
    },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [{ key: 'codigo', order: 'asc' }];

const opcionesAccion = [
    {
        permiso: acc.editar,
        ...accionEditObject,
    },
    {
        permiso: acc.oferta,
        title: t('semestre._oferta_'),
        text: t('semestre._oferta_descripcion_'),
        emitAction: 'oferta',
        color: 'indigo-darken-2',
        icon: 'mdi-format-list-text',
    },
    {
        permiso: acc.calendarizar,
        title: t('semestre._calendarizar_'),
        text: t('semestre._calendarizar_descripcion_'),
        emitAction: 'calendarizar',
        color: 'brown',
        icon: 'mdi-calendar-month-outline',
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
    <AppLayout :titulo="mensajes.titulo2" :subtitulo="mensajes.subtitulo" icono="mdi-calendar-text-outline">
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
                    <v-sheet v-if="step === 3">
                        <SemestreForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :accion="selectedAction"
                            @form-saved="handleFormSave"
                        ></SemestreForm>
                        <SemestreOferta v-if="selectedAction === 'oferta'" :item="selectedItem" :accion="selectedAction"></SemestreOferta>
                        <SemestreShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></SemestreShow>
                        <Evento v-if="selectedAction == 'calendarizar'" :idCalendario="selectedItem.calendarizacion_id"></Evento>
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
