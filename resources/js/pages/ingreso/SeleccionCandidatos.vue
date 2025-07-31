<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { saveAs } from 'file-saver';
import { ref, watch } from 'vue';
import * as XLSX from 'xlsx';

interface Departamento {
    codigo_depto: string;
    departamento: string;
}
interface Props {
    departamentos?: Departamento[];
    opcionesBachillerato?: [];
}

const form = ref({
    departamentos: [],
    opciones: [],
});

const candidatos = ref([]);

const step = ref(1);
const formChanged = ref(true);
const props = defineProps<Props>();
const search = ref('');

const headers = [
    { title: 'NIE', key: 'nie', align: 'start' },
    { title: 'Estudiante', key: 'primer_nombre' },
    { title: 'Correo', key: 'correo' },
    { title: 'Sexo', key: 'sexo' },
    { title: 'Edad', key: 'edad', align: 'end' },
    { title: 'Sector', key: 'sector' },
    { title: 'Centro Educativo', key: 'nombre_centro_educativo' },
    { title: 'Opción', key: 'opcion_bachillerato' },
    { title: 'Nota', key: 'nota_promocion' },
    { title: 'Seleccionar', key: 'invitado', align: 'end', sortable: false },
];

const sortBy = [{ key: 'nota_promocion', order: 'desc' }];

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
</script>

<template>
    <Head title="Selección de candidatos"></Head>
    <AppLayout>
        <v-form fast-fail @submit.prevent="getCandidatos">
            <v-stepper
                editable
                :items="['Parámetros de agrupación', 'Selección de candidatos']"
                v-model="step"
                show-actions
                prev-text="Anterior"
                next-text="Continuar"
                alt-labels
                color="#333"
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
                            :sort-by="sortBy"
                            multi-sort
                            hover
                            striped="odd"
                        >
                            <template v-slot:item.primer_nombre="{ item }">
                                <div class="d-flex ga-2">
                                    {{ nombreCompleto(item) }}
                                </div>
                            </template>
                            <template v-slot:item.invitado="{ item }">
                                <v-checkbox-btn v-model="item.invitado" :ripple="false"></v-checkbox-btn>
                            </template>
                        </v-data-table>
                    </v-card>
                </template>
                <template v-slot:next></template>
                <template v-slot:prev>
                    <v-row justify="start">
                        <v-col cols="auto">
                            <v-btn
                                @click="step--"
                                :disabled="step == 1"
                                rounded
                                variant="tonal"
                                color="blue-darken-4"
                                prepend-icon="mdi-arrow-left-bold"
                            >
                                <template v-slot:prepend>
                                    <v-icon color="success"></v-icon>
                                </template>
                                Atrás
                            </v-btn>
                        </v-col>

                        <v-col cols="auto">
                            <v-btn
                                @click="step++"
                                :disabled="step != 1"
                                rounded
                                variant="tonal"
                                color="blue-darken-4"
                                append-icon="mdi-arrow-right-bold"
                            >
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
    </AppLayout>
</template>
