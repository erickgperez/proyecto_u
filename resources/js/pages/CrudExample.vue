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
const sheetName = ref('Listado de libros');
const fileName = ref('libros');

const selectedItemLabel = computed(() => selectedItem.value.title ?? '');

// Título del listado
const titleList = ref('Listado de libros');
function createNewRecord() {
    return {
        title: '',
        author: '',
        genre: '',
        year: currentYear,
        pages: 1,
    };
}
const headers = [
    { title: 'Título', key: 'title', align: 'start' },
    { title: 'Autor', key: 'author' },
    { title: 'Género', key: 'genre' },
    { title: 'Año', key: 'year', align: 'end' },
    { title: 'Páginas', key: 'pages', align: 'end' },
    { title: 'Acciones', key: 'actions', align: 'end', sortable: false },
];

const sortBy = [
    { key: 'year', order: 'desc' },
    { key: 'title', order: 'desc' },
];

function reset() {
    dialog.value = false;
    formModel.value = createNewRecord();
    items.value = [
        { id: 1, title: 'To Kill a Mockingbird', author: 'Harper Lee', genre: 'Fiction', year: 1960, pages: 281 },
        { id: 2, title: '1984', author: 'George Orwell', genre: 'Dystopian', year: 1949, pages: 328 },
        { id: 3, title: 'The Great Gatsby', author: 'F. Scott Fitzgerald', genre: 'Fiction', year: 1925, pages: 180 },
        { id: 4, title: 'Sapiens', author: 'Yuval Noah Harari', genre: 'Non-Fiction', year: 2011, pages: 443 },
        { id: 5, title: 'Dune', author: 'Frank Herbert', genre: 'Sci-Fi', year: 1965, pages: 412 },
    ];
}

function edit(id) {
    const found = items.value.find((item) => item.id === id);

    formModel.value = {
        id: found.id,
        title: found.title,
        author: found.author,
        genre: found.genre,
        year: found.year,
        pages: found.pages,
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

function save() {
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
    <Head title="Ejemplo CRUD"> </Head>
    <AppLayout titulo="Ejemplo de CRUD" subtitulo="Página que servirá de plantilla para el funcionamiento de CRUDs" icono="mdi-wrench-clock">
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
                        <v-btn icon="mdi-table-plus" color="success" class="ml-2" title="Agregar nuevo registro" @click="add"></v-btn>
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
            <v-card :subtitle="`${isEditing ? 'Update' : 'Create'} your favorite book`" :title="`${isEditing ? 'Edit' : 'Add'} a Book`">
                <template v-slot:text>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field v-model="formModel.title" label="Title"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field v-model="formModel.author" label="Author"></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-select v-model="formModel.genre" :items="['Fiction', 'Dystopian', 'Non-Fiction', 'Sci-Fi']" label="Genre"></v-select>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-number-input v-model="formModel.year" :max="currentYear" :min="1" label="Year"></v-number-input>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-number-input v-model="formModel.pages" :min="1" label="Pages"></v-number-input>
                        </v-col>
                    </v-row>
                </template>

                <v-divider></v-divider>

                <v-card-actions class="bg-surface-light">
                    <v-btn text="Cancel" variant="plain" @click="dialog = false"></v-btn>

                    <v-spacer></v-spacer>

                    <v-btn text="Save" @click="save"></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>
