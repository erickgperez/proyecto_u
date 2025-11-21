<script setup lang="ts">
import PersonaDatosContactoForm from '@/components/administracion/PersonaDatosContactoForm.vue';
import PersonaDocumentos from '@/components/administracion/PersonaDocumentos.vue';
import PersonaForm from '@/components/administracion/PersonaForm.vue';
import PersonaRegistroDocente from '@/components/administracion/PersonaRegistroDocente.vue';
import PersonaShow from '@/components/administracion/PersonaShow.vue';
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
    nombre: string;
    apellidos: string;
    sexo: string | null;
    fecha_nacimiento: Date | null;
    edad: string;
}

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    sexos: Array,
    distritosTree: Array,
    tiposDocumento: Array,
});
const itemVacio = ref<Item>({
    id: null,
    nombre: '',
    apellidos: '',
    sexo: '',
    fecha_nacimiento: null,
    edad: '',
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() => selectedItem.value?.nombreCompleto ?? '');
const rutaBorrar = ref('administracion-persona-delete');
const mensajes = {
    titulo1: t('persona._singular_'),
    titulo2: t('persona._administrar_'),
    subtitulo: t('persona._permite_gestionar_datos_'),
    tituloListado: t('persona._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ADMINISTRACION_PERSONA_EDITAR',
    mostrar: 'ADMINISTRACION_PERSONA_MOSTRAR',
    borrar: 'ADMINISTRACION_PERSONA_BORRAR',
    datos_contacto: 'ADMINISTRACION_PERSONA_DATOS-CONTACTO',
    documentos: 'ADMINISTRACION_PERSONA_DOCUMENTOS',
    registro_docente: 'ADMINISTRACION_PERSONA_REGISTRO-DOCENTE',
};
const permisoAny = 'ADMINISTRACION_PERSONA_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ADMINISTRACION_PERSONA',
    crear: 'ADMINISTRACION_PERSONA_CREAR',
    exportar: 'ADMINISTRACION_PERSONA_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_personas');
const fileName = ref('personas');

const headers = [
    { title: t('_id_'), key: 'id' },
    { title: t('persona._nombre_'), key: 'nombre', align: 'start' },
    { title: t('persona._apellidos_'), key: 'apellidos', align: 'start' },
    { title: t('persona._sexo_'), key: 'sexo.descripcion' },
    { title: t('persona._fecha_nacimiento_'), key: 'fecha_nacimiento' },
    { title: t('persona._edad_'), key: 'edad', align: 'end' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [
    { key: 'apellidos', order: 'asc' },
    { key: 'nombre', order: 'asc' },
];

const opcionesAccion = [
    {
        permiso: acc.datos_contacto,
        title: t('persona._datos_contacto_'),
        text: t('persona._datos_contacto_descripcion_'),
        emitAction: 'datos-contacto',
        color: 'purple-darken-4',
        icon: 'mdi-calendar-month-outline',
    },
    {
        permiso: acc.documentos,
        title: t('persona._documentos_'),
        text: t('persona._documentos_descripcion_'),
        emitAction: 'documentos',
        color: 'brown-darken-1',
        icon: 'mdi-file-document-outline',
    },
    {
        permiso: acc.registro_docente,
        title: t('persona._registro_docente_'),
        text: t('persona._registro_docente_descripcion_'),
        emitAction: 'registro-docente',
        color: 'brown-lighten-1',
        icon: 'mdi-human-male-board',
    },
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
    <AppLayout :titulo="mensajes.titulo2" :subtitulo="mensajes.subtitulo" icono="mdi-account-group-outline">
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
                        <template v-slot:item.fecha_nacimiento="{ value }">
                            <div class="d-flex ga-2">
                                {{ value !== null ? date.format(value, 'keyboardDate') : '' }}
                            </div>
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
                        :selectedItemId="selectedItem.uuid"
                    ></Acciones>
                    <v-alert v-else border="top" type="warning" variant="outlined" prominent>
                        {{ $t('_no_tiene_permiso_para_realizar_ninguna_accion_') }}
                    </v-alert>
                </v-window-item>

                <!-- *********************** CRUD PARTE 3: EJECUTAR ACCIONES ******************************-->
                <v-window-item :value="3">
                    <v-sheet v-if="step === 3">
                        <PersonaDatosContactoForm
                            v-if="selectedAction === 'datos-contacto'"
                            :item="selectedItem"
                            :accion="selectedAction"
                            :distritosTree="distritosTree"
                            @form-saved="handleFormSave"
                        ></PersonaDatosContactoForm>
                        <PersonaForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :accion="selectedAction"
                            :sexos="props.sexos"
                            @form-saved="handleFormSave"
                        ></PersonaForm>
                        <PersonaShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></PersonaShow>
                        <PersonaDocumentos
                            v-if="selectedAction == 'documentos'"
                            :item="selectedItem"
                            :accion="selectedAction"
                            :tipos-documento="tiposDocumento"
                        ></PersonaDocumentos>
                        <PersonaRegistroDocente
                            v-if="selectedAction == 'registro-docente'"
                            :item="selectedItem"
                            :accion="selectedAction"
                        ></PersonaRegistroDocente>
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
