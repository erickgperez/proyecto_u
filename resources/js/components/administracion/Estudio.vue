<script setup lang="ts">
import EstudioForm from '@/components/administracion/EstudioForm.vue';
import EstudioShow from '@/components/administracion/EstudioShow.vue';
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import Listado from '@/components/crud/Listado.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { useFuncionesCrud } from '@/composables/useFuncionesCrud';
import { usePermissions } from '@/composables/usePermissions';
import type { SortBy } from '@/types/tipos';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission } = usePermissions();
const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();
const { t } = useI18n();

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
interface Item {
    id: number | null;
    uuid: string;
    nombre: string;
    institucion: string;
    fecha_finalizacion: Date | null;
}
const props = defineProps({
    persona: Object,
    items: Array,
});

const itemVacio = ref<Item>({
    id: null,
    uuid: '',
    nombre: '',
    institucion: '',
    fecha_finalizacion: null,
});

const { step, selectedAction, localItems, selectedItem, updateItems, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    [],
);

const selectedItemLabel = computed(() => selectedItem.value?.codigo ?? '');
const rutaBorrar = ref('administracion-estudio-delete');
const mensajes = {
    titulo1: t('estudio._plural_'),
    titulo2: t('estudio._administrar_'),
    subtitulo: t('estudio._permite_gestionar_'),
    tituloListado: t('estudio._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ADMINISTRACION_PERFIL_ESTUDIO_EDITAR',
    mostrar: 'ADMINISTRACION_PERFIL_ESTUDIO_MOSTRAR',
    borrar: 'ADMINISTRACION_PERFIL_ESTUDIO_BORRAR',
};
const permisoAny = 'ADMINISTRACION_PERFIL_ESTUDIO_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'ADMINISTRACION_PERFIL_ESTUDIO',
    crear: 'ADMINISTRACION_PERFIL_ESTUDIO_CREAR',
    exportar: 'ADMINISTRACION_PERFIL_ESTUDIO_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_estudios');
const fileName = ref('estudios');

const headers = [
    { title: t('estudio._nombre_'), key: 'nombre_estudio' },
    { title: t('estudio._institucion_'), key: 'nombre_institucion' },
    { title: t('estudio._fecha_finalizacion_'), key: 'fecha_finalizacion' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [];

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

onMounted(() => {
    //Obtener los estudios de la persona con axios
    axios
        .get(route('administracion-estudio-index', { uuid: props.persona.uuid }))
        .then(function (response) {
            updateItems(response.data.items);
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});
</script>

<template>
    <!--********************************************************************************
    ******** LA PLANTILLA GENERALMENTE NO SERÁ NECESARIO MODIFICAR
    ******** Solo para agregar/cambiar un elemento en la parte 3: Ejecutar acciones, o modificar la
    ******** presentación de un campo en el listado de la parte 1
    ************************************************************************************
    -->
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
                <v-sheet v-if="step === 3" class="bg-transparent">
                    <EstudioForm
                        v-if="selectedAction === 'new' || selectedAction === 'edit'"
                        :item="selectedAction === 'new' ? itemVacio : selectedItem"
                        :accion="selectedAction"
                        :persona="props.persona"
                        @form-saved="handleFormSave"
                    ></EstudioForm>
                    <EstudioShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></EstudioShow>
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
</template>
