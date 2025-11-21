<script setup lang="ts">
import UsuarioForm from '@/components/administracion/UsuarioForm.vue';
import UsuarioShow from '@/components/administracion/UsuarioShow.vue';
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
    name: string;
    email: string;
    personas: [];
}

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    personas: Array,
    roles: Array,
});
const itemVacio = ref<Item>({
    id: null,
    name: '',
    email: '',
    personas: [],
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items,
);

const selectedItemLabel = computed(() => selectedItem.value?.email ?? '');
const rutaBorrar = ref('seguridad-usuario-delete');
const mensajes = {
    titulo1: t('usuario._singular_'),
    titulo2: t('usuario._administrar_'),
    subtitulo: t('usuario._permite_gestionar_datos_'),
    tituloListado: t('usuario._listado_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'ADMINISTRACION_USUARIO_EDITAR',
    mostrar: 'ADMINISTRACION_USUARIO_MOSTRAR',
    borrar: 'ADMINISTRACION_USUARIO_BORRAR',
};
const permisoAny = 'ADMINISTRACION_USUARIO_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_ADMINISTRACION_USUARIO',
    crear: 'ADMINISTRACION_USUARIO_CREAR',
    exportar: 'ADMINISTRACION_USUARIO_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_usuarios');
const fileName = ref('usuarios');

const headers = [
    { title: t('_id_'), key: 'id' },
    { title: t('usuario._nombre_'), key: 'name', align: 'start' },
    { title: t('usuario._email_'), key: 'email', align: 'start' },
    { title: t('usuario._asignado_a_'), key: 'personas', align: 'start' },
    { title: t('rol._plural_'), key: 'roles', align: 'start' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [{ key: 'email', order: 'asc' }];

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
    <AppLayout :titulo="mensajes.titulo2" :subtitulo="mensajes.subtitulo" icono="mdi-card-account-details-outline">
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
                        <template v-slot:item.personas="{ value }">
                            <div class="d-flex ga-2" v-if="value.length > 0">
                                {{ value[0].nombreCompleto }}
                            </div>
                            <div v-else></div>
                        </template>
                        <template v-slot:item.roles="{ value }">
                            <div class="d-flex ga-2">
                                <v-list density="compact" class="transparent-list" :items="value" item-title="name"> </v-list>
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
                        <UsuarioForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :accion="selectedAction"
                            :personas="personas"
                            :roles="roles"
                            @form-saved="handleFormSave"
                        ></UsuarioForm>
                        <UsuarioShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></UsuarioShow>
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
<style scoped>
.transparent-list {
    background-color: transparent !important;
}
</style>
