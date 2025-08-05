<script setup lang="ts">
import axios from 'axios';
import { saveAs } from 'file-saver';
import Swal from 'sweetalert2';
import { nextTick, ref, watch } from 'vue';
import * as XLSX from 'xlsx';

interface Departamento {
    codigo_depto: string;
    departamento: string;
}
interface Props {
    departamentos?: Departamento[];
    opcionesBachillerato?: [];
}
const props = defineProps<Props>();

const form = ref({
    departamentos: [],
    opciones: [],
});

const candidatos = ref([]);
const step = ref(1);
const formChanged = ref(true);

const search = ref('');

const headers = [
    { title: '#', key: 'row_number', align: 'start' },
    { title: 'NIE', key: 'nie', align: 'start' },
    { title: 'Estudiante', key: 'primer_nombre' },
    { title: 'Correo', key: 'correo' },
    { title: 'Sexo', key: 'sexo' },
    //{ title: 'Edad', key: 'edad', align: 'end' },
    { title: 'Sector', key: 'sector' },
    { title: 'Centro Educativo', key: 'nombre_centro_educativo' },
    { title: 'Opción', key: 'opcion_bachillerato' },
    { title: 'Invitar', key: 'invitado', align: 'end', sortable: false },
];

//const sortBy = [];

watch(step, () => {
    if (step.value == 2 && formChanged.value) {
        getCandidatos();
        formChanged.value = false;
    }
});

watch(form.value, () => {
    formChanged.value = true;
});

async function getCandidatos() {
    //console.log('obtener listado de candidatos');
    candidatos.value = [];
    try {
        const response = await axios.postForm(route('ingreso-bachillerato-candidatos-listado'), form.value);
        candidatos.value = response.data;
    } catch (error) {
        console.error(error);
    }
}

const exportToExcel = () => {
    const worksheet = XLSX.utils.json_to_sheet(candidatos.value);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Listado');

    // Generate a binary string
    const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

    // Create a Blob and save the file
    const data = new Blob([excelBuffer], { type: 'application/octet-stream' });
    saveAs(data, 'Listado-candidatos.xlsx');
};

const nombreCompleto = (item: any) => {
    return [item.primer_nombre, item.segundo_nombre, item.tercer_nombre, item.primer_apellido, item.segundo_apellido, item.tercer_apellido]
        .filter((x) => x != null)
        .join(' ');
};

const toggleSeleccion = (item: any) => {
    item.invitado = !item.invitado;

    axios.patch(route('ingreso-bachillerato-candidato-save-field'), {
        nie: item.nie,
        invitado: item.invitado,
        campo: 'invitado',
    });
};

const editedItem = ref(null);
const editField = ref(null);

function startEditing(item: any) {
    editedItem.value = { ...item }; // copia

    // Esperar al próximo ciclo para que el input esté montado
    nextTick(() => {
        if (editField.value) {
            editField.value.focus();
        }
    });
}

function saveItem(campo: string) {
    const valid = ref(true);

    if (campo === 'correo' && editedItem.value && editedItem.value.correo !== null && editedItem.value.correo !== '') {
        //validar el correo
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        valid.value = emailRegex.test(editedItem.value.correo);
    }
    if (valid.value) {
        const index = candidatos.value.findIndex((i) => i.nie === editedItem.value.nie);
        if (index !== -1) {
            candidatos.value[index] = { ...editedItem.value };

            axios.patch(route('ingreso-bachillerato-candidato-save-field'), {
                nie: editedItem.value.nie,
                correo: editedItem.value.correo,
                campo: 'correo',
            });
        }
        editedItem.value = null;
    } else {
        Swal.fire({
            title: 'Error',
            position: 'top-end',
            text: 'Dirección de correo no válida',
            icon: 'error',
            showConfirmButton: false,
            timer: 1500,
        });
    }
}
</script>
<template>
    <v-form fast-fail @submit.prevent="getCandidatos">
        <v-stepper
            editable
            :items="['Parámetros de agrupación', 'Selección de candidatos']"
            v-model="step"
            show-actions
            prev-text="Anterior"
            next-text="Continuar"
            color="#333"
            alt-labels
        >
            <template v-slot:item.1>
                <v-container fluid>
                    <v-row>
                        <v-col cols="6">
                            <v-combobox
                                v-model="form.departamentos"
                                :items="props.departamentos"
                                item-title="departamento"
                                label="Departamento"
                                multiple
                                clearable
                            >
                                <template v-slot:selection="data">
                                    <v-chip size="small">
                                        <template v-slot:prepend>
                                            <v-avatar class="text-uppercase bg-blue" start>
                                                {{
                                                    data.item.title
                                                        .split(' ')
                                                        .map((w) => w.charAt(0))
                                                        .join('')
                                                }}
                                            </v-avatar>
                                        </template>
                                        {{ data.item.title }}
                                    </v-chip>
                                </template>
                            </v-combobox>
                        </v-col>
                        <v-col cols="6">
                            <v-combobox
                                v-model="form.opciones"
                                :items="props.opcionesBachillerato"
                                item-title="opcion"
                                label="Opciones de bachillerato"
                                multiple
                                clearable
                                chips
                                persistent-hint
                                hint="Si no elije ninguna opción, se mostrarán los candidatos para todos los tipos de bachillerato"
                            >
                            </v-combobox>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
            <template v-slot:item.2>
                <v-card>
                    <v-card-title class="d-flex align-center pe-2">
                        <v-icon icon="mdi-format-list-text"></v-icon> &nbsp; Listado de candidatos
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
                        :items="candidatos"
                        border="primary thin"
                        class="w-100"
                        multi-sort
                        hover
                        striped="odd"
                    >
                        <template v-slot:item.row_number="{ index }">
                            {{ index + 1 }}
                        </template>
                        <template v-slot:item.primer_nombre="{ item }">
                            {{ nombreCompleto(item) }}
                        </template>
                        <template v-slot:item.correo="{ item }">
                            <div v-if="editedItem?.nie === item.nie">
                                <v-text-field
                                    v-model="editedItem.correo"
                                    ref="editField"
                                    type="email"
                                    hide-details
                                    @keyup.enter="saveItem('correo')"
                                    @blur="saveItem('correo')"
                                    @keydown.esc="editedItem = null"
                                />
                            </div>
                            <div v-else-if="item.correo != null && item.correo != ''" @dblclick="startEditing(item)">
                                {{ item.correo }}
                            </div>
                            <div v-else class="justify-end">
                                <v-btn
                                    color="indigo"
                                    icon="mdi-email-plus-outline"
                                    size="small"
                                    title="Agregar dirección de correo electrónico"
                                    @click="startEditing(item)"
                                />
                            </div>
                        </template>
                        <template v-slot:item.invitado="{ item }">
                            <v-checkbox-btn
                                v-if="item.correo != null"
                                v-model="item.invitado"
                                :ripple="false"
                                @click="toggleSeleccion(item)"
                            ></v-checkbox-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </template>
            <template v-slot:next></template>
            <template v-slot:prev>
                <v-row justify="start">
                    <v-col cols="auto">
                        <v-btn @click="step--" :disabled="step == 1" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-arrow-left-bold">
                            <template v-slot:prepend>
                                <v-icon color="success"></v-icon>
                            </template>
                            Atrás
                        </v-btn>
                    </v-col>

                    <v-col cols="auto">
                        <v-btn @click="step++" :disabled="step != 1" rounded variant="tonal" color="blue-darken-4" append-icon="mdi-arrow-right-bold">
                            <template v-slot:append>
                                <v-icon color="success"></v-icon>
                            </template>
                            Siguiente
                        </v-btn>
                    </v-col>
                </v-row>
            </template>
        </v-stepper>
    </v-form>
</template>
