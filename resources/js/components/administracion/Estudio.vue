<script setup lang="ts">
import EstudioForm from '@/components/administracion/EstudioForm.vue';
import EstudioShow from '@/components/administracion/EstudioShow.vue';
import { useAccionesObject } from '@/composables/useAccionesObject';
import { useFunciones } from '@/composables/useFunciones';
import { usePermissions } from '@/composables/usePermissions';
import type { SortBy } from '@/types/tipos';
import axios from 'axios';
import Swal from 'sweetalert2';
import { computed, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';

const date = useDate();

const { hasPermission } = usePermissions();
const { mensajeExito, mensajeError } = useFunciones();
const { accionEditObject, accionShowObject, accionDeleteObject } = useAccionesObject();
const { t } = useI18n();

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************
interface Item {
    id: number | null;
    uuid: string;
    nombre_titulo: string;
    nombre_institucion: string;
    fecha_finalizacion: Date | null;
}
const props = defineProps({
    persona: Object,
});

const itemVacio = ref<Item>({
    id: null,
    uuid: '',
    nombre_titulo: '',
    nombre_institucion: '',
    fecha_finalizacion: null,
});

const selectedItem = ref(null);
const items = ref<Item[]>([]);
const step = ref(1);
const selectedAction = ref('');

const selectedItemLabel = computed(() => selectedItem.value?.nombre_titulo ?? '');
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
    { title: t('estudio._nombre_'), key: 'nombre_titulo' },
    { title: t('estudio._institucion_'), key: 'nombre_institucion' },
    { title: t('estudio._fecha_finalizacion_'), key: 'fecha_finalizacion' },
    { title: t('_acciones_'), key: 'actions', align: 'center' },
];

const sortBy: SortBy[] = [];
const search = ref('');

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
            items.value = response.data.items;
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});

function remove() {
    Swal.fire({
        title: t('_confirmar_borrar_registro_'),
        text: selectedItemLabel.value,
        showCancelButton: true,
        confirmButtonText: t('_borrar_'),
        cancelButtonText: t('_cancelar_'),
        confirmButtonColor: '#e5adac',
        cancelButtonColor: '#D7E1EE',
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const resp = await axios.delete(route(rutaBorrar, { id: selectedItem.uuid }));

                if (resp.data.status == 'ok') {
                    const index = items.value.findIndex((item) => item.id === selectedItem.value?.id);
                    items.value.splice(index, 1);

                    mensajeExito(t('_registro_eliminado_correctamente_'));
                } else {
                    throw new Error(t(resp.data.message));
                }
            } catch (error: any) {
                console.log(error);
                mensajeError(t('_no_se_pudo_eliminar_') + '. ' + error.message);
            }
        }
    });
}

const handleFormSave = (data: any) => {
    if (selectedAction.value == 'new') {
        items.value.push(data);
        step.value = 1;
    } else {
        const index = items.value.findIndex((item) => item.id === data.id);

        items.value[index] = data;
    }
};
</script>

<template>
    <!--********************************************************************************
    ******** LA PLANTILLA GENERALMENTE NO SERÁ NECESARIO MODIFICAR
    ******** Solo para agregar/cambiar un elemento en la parte 3: Ejecutar acciones, o modificar la
    ******** presentación de un campo en el listado de la parte 1
    ************************************************************************************
    -->
    <v-sheet v-if="hasPermission(permisos.listado)" class="elevation-12">
        <v-window v-model="step" class="h-auto w-100">
            <!-- ************************** CRUD PARTE 1: LISTADO *****************************-->
            <v-window-item :value="1">
                <v-data-iterator :items="items" item-value="id" :search="search">
                    <template v-slot:header>
                        <v-toolbar class="bg-blue-grey-lighten-3 rounded-t-xl px-2">
                            <v-icon icon="mdi-format-list-text"></v-icon> &nbsp; {{ props.persona.nombreCompleto }}
                            <v-spacer></v-spacer>

                            <v-text-field
                                v-model="search"
                                density="compact"
                                :label="$t('_buscar_')"
                                prepend-inner-icon="mdi-magnify"
                                variant="outlined"
                                rounded="xl"
                                flat
                                hide-details
                                single-line
                            ></v-text-field>
                            <v-btn
                                v-if="hasPermission(permisos.crear)"
                                icon="mdi-table-plus"
                                color="green-darken-3"
                                class="ml-2"
                                :title="$t('_crear_nuevo_registro_')"
                                @click="
                                    selectedItem = itemVacio;
                                    selectedAction = 'new';
                                    step = 3;
                                "
                            ></v-btn>
                        </v-toolbar>
                    </template>
                    <template v-slot:default="{ items }">
                        <span class="text-h6 pl-2">{{ $t('estudio._estudios_realizados_') }}</span>
                        <v-row>
                            <v-col v-for="item in items" :key="item.id" cols="12" md="6" sm="12">
                                <v-card class="ma-2 rounded-xl" variant="tonal" elevation="2">
                                    <v-card-title class="d-flex align-center">
                                        <h4>{{ item.raw.nombre_titulo }}</h4>
                                    </v-card-title>
                                    <v-card-subtitle>{{ date.format(item.raw.fecha_finalizacion, 'keyboardDate') }}</v-card-subtitle>

                                    <v-card-text> {{ $t('estudio._institucion_') }}: {{ item.raw.nombre_institucion }} </v-card-text>
                                    <v-card-actions>
                                        <v-btn
                                            v-if="hasPermission(permisos.editar)"
                                            icon="mdi-table-edit"
                                            color="primary"
                                            class="ml-2"
                                            :title="$t('_editar_')"
                                            @click="
                                                selectedItem = item.raw;
                                                selectedAction = 'edit';
                                                step = 3;
                                            "
                                        ></v-btn>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            v-if="hasPermission(permisos.borrar)"
                                            icon="mdi-table-remove"
                                            color="error"
                                            :title="$t('_borrar_')"
                                            @click="
                                                selectedItem = item.raw;
                                                remove();
                                            "
                                        ></v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </template>
                </v-data-iterator>
            </v-window-item>

            <!-- ********************* CRUD PARTE 2: ELEGIR ACCION A REALIZAR ****************************-->
            <v-window-item :value="2"> </v-window-item>

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
            <v-btn v-if="step == 3" prepend-icon="mdi-page-first" rounded variant="tonal" color="blue-darken-4" @click="step = 1" class="ml-2">
                <template v-slot:prepend>
                    <v-icon color="success"></v-icon>
                </template>
                {{ $t('estudio._regresar_') }}
            </v-btn>
        </v-card-actions>
    </v-sheet>
    <v-alert v-else border="top" type="warning" variant="outlined" prominent>
        {{ $t('_no_tiene_permiso_para_esta_accion_') }}
    </v-alert>
</template>
