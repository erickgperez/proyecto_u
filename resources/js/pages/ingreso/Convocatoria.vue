<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { saveAs } from 'file-saver';
import { computed, onMounted, ref, shallowRef, toRef } from 'vue';
import * as XLSX from 'xlsx';

const step = ref(1);

const selectedItem = ref('');
const selectedAction = ref('');

const search = ref('');
const loading = ref(false);

const currentYear = new Date().getFullYear();

const exportToExcel = () => {
    const worksheet = XLSX.utils.json_to_sheet(items.value);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, sheetName.value);

    // Generate a binary string
    const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

    // Create a Blob and save the file
    const data = new Blob([excelBuffer], { type: 'application/octet-stream' });
    saveAs(data, fileName.value + '.xlsx');
};

const items = ref([]);
const formModel = ref(createNewRecord());
const dialog = shallowRef(false);
const isEditing = toRef(() => !!formModel.value.id);

// *************************************************************************************************************
// **************** Sección que se debe adecuar para cada CRUD específico***************************************
// *************************************************************************************************************

// Nombre de hoja y archivo a utilizar cuando se guarde el listado como excel
const sheetName = ref('Listado_convocatorias');
const fileName = ref('convocatorias');

const selectedItemLabel = computed(() => selectedItem.value.title ?? '');

// Título del listado
const titleList = ref('Listado de convocatorias');
function createNewRecord() {
    return {
        nombre: '',
        descripcion: '',
        fecha: '',
        cuerpo_mensaje: '',
        afiche: '',
    };
}
const headers = [
    { title: 'fecha', key: 'fecha' },
    { title: 'Nombre', key: 'nombre', align: 'start' },
    { title: 'Descripción', key: 'descripcion' },
];

const sortBy = [
    { key: 'fecha', order: 'asc' },
    { key: 'nombre', order: 'asc' },
];

function reset() {
    dialog.value = false;
    formModel.value = createNewRecord();
    items.value = [];
}

function edit(id) {
    const found = items.value.find((item) => item.id === id);

    formModel.value = {
        id: found.id,
        nombre: found.nombre,
        descripcion: found.descripcion,
        fecha: found.fecha,
        cuerpo_mensaje: found.cuerpo_mensaje,
        afiche: found.afiche,
    };

    dialog.value = true;
}

// ***********************************************

function add() {
    formModel.value = createNewRecord();
    dialog.value = true;
}

function remove(id: number) {
    const index = items.value.findIndex((item) => item.id === id);
    items.value.splice(index, 1);
}

function submitForm() {
    if (isEditing.value) {
        const index = items.value.findIndex((item) => item.id === formModel.value.id);
        items.value[index] = formModel.value;
    } else {
        formModel.value.id = items.value.length + 1;
        items.value.push(formModel.value);
    }

    dialog.value = false;
}

const selectItem = (item: any) => {
    selectedItem.value = item;

    step.value++;
};

const selectAction = (accion: string) => {
    selectedAction.value = accion;
    step.value++;
};

onMounted(() => {
    reset();
});
</script>

<template>
    <Head title="Convocatoria"> </Head>
    <AppLayout titulo="Gestionar Convocatoria" subtitulo="Administrar las convocatorias" icono="mdi-wrench-clock">
        <v-window v-model="step" class="h-auto w-100">
            <v-window-item :value="1">
                <v-card>
                    <v-card-title class="d-flex align-center pe-2">
                        <v-icon icon="mdi-format-list-text"></v-icon> &nbsp; {{ titleList }}
                        <v-spacer></v-spacer>

                        <v-text-field
                            v-model="search"
                            density="compact"
                            label="Buscar"
                            prepend-inner-icon="mdi-magnify"
                            variant="outlined"
                            rounded="xl"
                            flat
                            hide-details
                            single-line
                        ></v-text-field>
                        <v-btn icon="mdi-table-plus" color="success" class="ml-2" title="Agregar nueva convocatoria" @click="add"></v-btn>
                        <v-btn
                            icon="mdi-file-export-outline"
                            color="primary"
                            variant="tonal"
                            class="ma-2"
                            title="Exportar"
                            @click="exportToExcel"
                        ></v-btn>
                    </v-card-title>

                    <v-data-table
                        v-model:search="search"
                        :headers="headers"
                        :items="items"
                        border="primary thin"
                        class="w-100"
                        :sort-by="sortBy"
                        multi-sort
                        hover
                        striped="odd"
                    >
                        <template v-slot:item.actions="{ item }">
                            <div class="d-flex ga-2 justify-end">
                                <v-icon color="primary" icon="mdi-pencil" size="small" @click="edit(item.id)"></v-icon>

                                <v-icon color="error" icon="mdi-delete" size="small" @click="remove(item.id)"></v-icon>
                                <v-icon
                                    color="green-darken-2"
                                    icon="mdi-chevron-right-circle-outline"
                                    size="large"
                                    @click="selectItem(item)"
                                ></v-icon>
                            </div>
                        </template>
                    </v-data-table>
                </v-card>
            </v-window-item>

            <v-window-item :value="2">
                <v-card class="align-center justify-center" :title="'Registro seleccionado: ' + selectedItemLabel">
                    <v-row dense>
                        <v-col cols="12" md="12">
                            <span class="text-h6">
                                <br />
                                <span>Elija la acción a realizar</span>
                            </span>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card
                                class="mx-auto"
                                subtitle="Editar la información del registro seleccionado"
                                title="Editar"
                                @click="selectAction('edit')"
                            >
                                <template v-slot:prepend>
                                    <v-avatar color="blue-darken-2">
                                        <v-icon icon="mdi-text-box-edit" size="x-large"></v-icon>
                                    </v-avatar>
                                </template>
                                <template v-slot:append>
                                    <v-icon color="success" icon="mdi-check"></v-icon>
                                </template>
                            </v-card>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-card class="mx-auto" subtitle="Mostrar los datos solo de lectura" title="Ver" @click="selectAction('show')">
                                <template v-slot:prepend>
                                    <v-avatar color="teal-lighten-4">
                                        <v-icon icon="mdi-eye-outline" size="x-large"></v-icon>
                                    </v-avatar>
                                </template>
                                <template v-slot:append>
                                    <v-icon color="success" icon="mdi-check"></v-icon>
                                </template>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card class="mx-auto" subtitle="Borrar el registro seleccionado" title="Eliminar" @click="selectAction('delete')">
                                <template v-slot:prepend>
                                    <v-avatar color="red-lighten-1">
                                        <v-icon icon="mdi-delete-alert" size="x-large"></v-icon>
                                    </v-avatar>
                                </template>
                                <template v-slot:append>
                                    <v-icon color="success" icon="mdi-check"></v-icon>
                                </template>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-card>
            </v-window-item>

            <v-window-item :value="3">
                <v-card title="Realizar acción">
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-card color="indigo" variant="outlined" class="md-6 mx-auto">
                                <v-card-item>
                                    <div>
                                        <div class="text-overline mb-1">Datos del registro</div>
                                        <div class="text-h6 mb-1">{{ selectedItem }}</div>
                                    </div>
                                </v-card-item>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card color="indigo" variant="elevated" class="md-6 mx-auto">
                                <v-card-item>
                                    <div>
                                        <div class="text-overline mb-1">Acción seleccionada</div>
                                        <div class="text-h6 mb-1">{{ selectedAction }}</div>
                                    </div>
                                </v-card-item>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-card>
            </v-window-item>
        </v-window>

        <v-divider></v-divider>

        <v-card-actions>
            <v-btn v-if="step > 1" prepend-icon="mdi-arrow-left-bold" rounded variant="tonal" color="blue-darken-4" @click="step--">
                <template v-slot:prepend>
                    <v-icon color="success"></v-icon>
                </template>
                Regresar
            </v-btn>
            <v-btn v-if="step == 3" prepend-icon="mdi-page-first" rounded variant="tonal" color="blue-darken-4" @click="step = 1">
                <template v-slot:prepend>
                    <v-icon color="success"></v-icon>
                </template>
                Ir al inicio
            </v-btn>
        </v-card-actions>

        <!-- ********************************** ADECUAR EL FORMULARIO SEGÚN SE REQUIERA ********************-->
        <v-dialog v-model="dialog" max-width="500">
            <v-card :subtitle="`${isEditing ? 'Actualizar' : 'Crear'} convocatoria`" :title="`${isEditing ? 'Editar' : 'Agregar'} convocatoria`">
                <template v-slot:text>
                    <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                        <v-row>
                            <v-col cols="12">
                                <v-text-field v-model="formModel.fecha" label="Fecha"></v-text-field>
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field v-model="formModel.nombre" label="Author"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-form>
                </template>

                <v-divider></v-divider>

                <v-card-actions class="bg-surface-light">
                    <v-btn text="Cancelar" variant="plain" @click="dialog = false"></v-btn>

                    <v-spacer></v-spacer>

                    <v-btn :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>
