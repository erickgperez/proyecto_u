<script setup lang="ts">
import Acciones from '@/components/crud/Acciones.vue';
import BotonesNavegacion from '@/components/crud/BotonesNavegacion.vue';
import Listado from '@/components/crud/Listado.vue';
import Evento from '@/components/Evento.vue';
import ConvocatoriaConfiguracionForm from '@/components/ingreso/ConvocatoriaConfiguracionForm.vue';
import ConvocatoriaForm from '@/components/ingreso/ConvocatoriaForm.vue';
import ConvocatoriaOfertaForm from '@/components/ingreso/ConvocatoriaOfertaForm.vue';
import ConvocatoriaShow from '@/components/ingreso/ConvocatoriaShow.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { useFuncionesCrud } from '@/composables/useFuncionesCrud';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import type { SortBy } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';

const date = useDate();
const { hasPermission } = usePermissions();
const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();
const { t } = useI18n();

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
interface Item {
    id: number | null;
    nombre: string;
    activa: boolean;
    descripcion: string;
    cuerpo_mensaje: string;
    etapa: string;
    solicitud: object | null;
    afiche: string | null;
    calendarizacion_id: number | null;
    carreras_sedes: [];
    created_at: Date | null;
}

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
        default: () => [],
    },
    sedesCarreras: Array,
    flujos: Array,
    pruebasBachillerato: Array,
});
const itemVacio = ref<Item>({
    id: null,
    nombre: '',
    activa: true,
    descripcion: '',
    etapa: '',
    solicitud: null,
    cuerpo_mensaje: '',
    afiche: null,
    calendarizacion_id: null,
    carreras_sedes: [],
    created_at: null,
});

const { step, selectedAction, localItems, selectedItem, handleAction, handleNextStep, selectItem, handleFormSave } = useFuncionesCrud(
    itemVacio,
    props.items.map((item: any) => {
        return { ...item, etapa: item.solicitud ? item.solicitud.etapa.codigo : '' };
    }),
);

const dialog = ref(false);
const itemDialog = ref<Item | null>(null);
const selectedItemLabel = computed(() => selectedItem.value?.nombre ?? '');
const rutaBorrar = ref('ingreso-convocatoria-delete');
const mensajes = {
    titulo1: t('convocatoria._convocatoria_'),
    titulo2: t('_administrar_convocatoria_'),
    subtitulo: t('_permite_gestionar_datos_convocatorias_'),
    tituloListado: t('_listado_convocatorias_'),
};

//Acciones que se pueden realizar al seleccionar un registro
const acc = {
    editar: 'INGRESO_CONVOCATORIA_EDITAR',
    mostrar: 'INGRESO_CONVOCATORIA_MOSTRAR',
    borrar: 'INGRESO_CONVOCATORIA_BORRAR',
    calendarizar: 'INGRESO_CONVOCATORIA_CALENDARIZAR',
    oferta: 'INGRESO_CONVOCATORIA_OFERTA',
    configuracion: 'INGRESO_CONVOCATORIA_CONFIGURACION',
};
const permisoAny = 'INGRESO_CONVOCATORIA_';
// Permisos requeridos por la interfaz
const permisos = {
    listado: 'MENU_INGRESO_CONVOCATORIA_GESTIONAR',
    crear: 'INGRESO_CONVOCATORIA_CREAR',
    exportar: 'INGRESO_CONVOCATORIA_EXPORTAR',
    acciones: [acc.editar, acc.borrar, acc.mostrar],
    editar: acc.editar,
    mostrar: acc.mostrar,
    borrar: acc.borrar,
};

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_convocatorias');
const fileName = ref('convocatorias');

const headers = [
    { title: t('convocatoria._fecha_creacion_'), key: 'created_at' },
    { title: t('_nombre_'), key: 'nombre', align: 'start' },
    { title: t('_descripcion_'), key: 'descripcion' },
    { title: t('convocatoria._activa_'), key: 'activa' },
    { title: t('etapa._singular_'), key: 'etapa' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [{ key: 'nombre', order: 'asc' }];

const opcionesAccion = [
    {
        permiso: acc.oferta,
        title: t('convocatoria._oferta_'),
        text: t('convocatoria._oferta_descripcion_'),
        emitAction: 'oferta',
        color: 'orange-darken-3',
        icon: 'mdi-format-list-text',
    },
    {
        permiso: acc.configuracion,
        title: t('convocatoria._configuracion_'),
        text: t('convocatoria._configuracion_descripcion_'),
        emitAction: 'configuracion',
        color: 'indigo-darken-2',
        icon: 'mdi-cog',
    },
    {
        permiso: acc.calendarizar,
        title: t('convocatoria._calendarizar_'),
        text: t('convocatoria._calendarizar_descripcion_'),
        emitAction: 'calendarizar',
        color: 'brown',
        icon: 'mdi-calendar-month-outline',
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

onMounted(() => {});
</script>

<template>
    <!--********************************************************************************
    ******** LA PLANTILLA GENERALMENTE NO SERÁ NECESARIO MODIFICAR
    ******** Solo para agregar/cambiar un elemento en la parte 3: Ejecutar acciones, o modificar la
    ******** presentación de un campo en el listado de la parte 1
    ************************************************************************************
    -->
    <Head :title="mensajes.titulo1"> </Head>
    <AppLayout :titulo="mensajes.titulo2" :subtitulo="mensajes.subtitulo" icono="mdi-book-settings-outline">
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
                        <template v-slot:item.etapa="{ value, item }">
                            <div class="d-flex ga-2" v-if="value">
                                {{ value }}
                                <v-icon
                                    :title="$t('_indicaciones_')"
                                    size="small"
                                    icon="mdi-information-slab-circle-outline"
                                    @click="
                                        itemDialog = item;
                                        dialog = true;
                                    "
                                ></v-icon>
                            </div>
                        </template>
                        <template v-slot:item.created_at="{ value }">
                            <div class="d-flex ga-2" v-if="value">
                                {{ date.format(value, 'keyboardDateTime12h') }}
                            </div>
                        </template>
                        <template v-slot:item.activa="{ value }">
                            <div class="d-flex ga-2">
                                <v-icon icon="mdi-checkbox-marked-outline" color="success" v-if="value"></v-icon>
                                <v-icon icon="mdi-close-box-outline" color="red" v-else></v-icon>
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
                        <ConvocatoriaForm
                            v-if="selectedAction === 'new' || selectedAction === 'edit'"
                            :item="selectedAction === 'new' ? itemVacio : selectedItem"
                            :accion="selectedAction"
                            :flujos="flujos"
                            @form-saved="handleFormSave"
                        ></ConvocatoriaForm>
                        <ConvocatoriaOfertaForm
                            v-if="selectedAction === 'oferta'"
                            :item="selectedItem"
                            :accion="selectedAction"
                            :sedesCarreras="props.sedesCarreras"
                            @form-saved="handleFormSave"
                        ></ConvocatoriaOfertaForm>
                        <ConvocatoriaConfiguracionForm
                            v-if="selectedAction === 'configuracion'"
                            :item="selectedItem"
                            :accion="selectedAction"
                            :pruebasBachillerato="pruebasBachillerato"
                            @form-saved="handleFormSave"
                        ></ConvocatoriaConfiguracionForm>

                        <ConvocatoriaShow v-if="selectedAction == 'show'" :item="selectedItem" :accion="selectedAction"></ConvocatoriaShow>
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
        <v-dialog v-model="dialog" width="auto" max-width="800">
            <v-card v-if="itemDialog">
                <v-card-text v-if="itemDialog.solicitud">
                    <span class="font-weight-black">{{ $t('etapa._singular_') }}:</span> <span>{{ itemDialog.solicitud.etapa.codigo }}</span>
                    <v-divider></v-divider>
                    <span class="font-weight-black">{{ $t('estado._singular_') }}:</span> <span>{{ itemDialog.solicitud.estado.codigo }}</span>
                    <v-divider></v-divider>
                    <span class="font-weight-black">{{ $t('etapa._indicaciones_') }}:</span> {{ itemDialog.solicitud.etapa.indicaciones }}
                    <v-divider class="mb-6"></v-divider>
                    <span class="font-weight-bold">{{ $t('_historial_') }}</span>
                    <v-timeline align="start" side="end" v-if="itemDialog.solicitud.historial.length > 0">
                        <v-timeline-item dot-color="teal-lighten-3" size="small" v-for="h in itemDialog.solicitud.historial" :key="id">
                            <template v-slot:opposite>
                                <div class="font-weight-bold">{{ date.format(h.created_at, 'keyboardDateTime12h') }}</div>
                            </template>
                            <div class="d-flex">
                                <div>
                                    <strong>{{ h.etapa.codigo }}</strong>
                                    <div class="text-caption mb-2">{{ h.estado.codigo }}</div>
                                    <div class="text-caption mb-2">{{ h.creator.name }}</div>
                                </div>
                            </div>
                        </v-timeline-item>
                    </v-timeline>
                </v-card-text>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>
