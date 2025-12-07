<script setup lang="ts">
import TablaDinamica from '@/components/reportes/TablaDinamica.vue';
import { SortBy } from '@/types/tipos';
import axios from 'axios';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import * as XLSX from 'xlsx';

const { t } = useI18n();

const props = defineProps({
    step: Number,
    convocatoria: Object,
    estadoSeleccion: Array,
    sedeSeleccion: Array,
    carreraSeleccion: Array,
});

defineEmits(['update:step']);

const headers = ref([
    { title: '#', key: 'index', sortable: false, visible: true },
    { title: t('aspirante._nie_'), key: 'nie', visible: true },
    { title: t('usuario._email_'), key: 'email', visible: false },
    { title: t('_nombre_'), key: 'nombre', visible: true },
    { title: t('_sexo_'), key: 'sexo', visible: true },
    { title: t('_sector_'), key: 'sector', visible: true },
    { title: t('sede._sede_'), key: 'sede', visible: props.sedeSeleccion && props.sedeSeleccion.length !== 1 },
    { title: t('aspirante._estado_'), key: 'seleccionado', visible: true },
    { title: t('carrera._singular_'), key: 'carrera', visible: props.carreraSeleccion && props.carreraSeleccion.length !== 1 },
]);
const visibleHeaders = computed(() => headers.value.filter((header) => header.visible));
const items = ref([]);

const sortBy: SortBy[] = [];

const loading = ref(false);
const error = ref<string | null>(null);
let controller: AbortController | null = null;
const tab = ref(null);

const isVisibleColumn = (column: string) => {
    return headers.value.find((header) => header.key === column)?.visible;
};

const itemsTransformados = computed(() =>
    items.value.map((i) => {
        const transformedItem: Record<string, any> = {};
        if (isVisibleColumn('nie')) {
            transformedItem.nie = i.solicitante?.nie;
        }
        if (isVisibleColumn('email')) {
            transformedItem.email = i.solicitante?.persona?.usuarios[0].email;
        }
        if (isVisibleColumn('nombre')) {
            transformedItem.nombre = i.solicitante?.persona?.nombreCompleto;
        }
        if (isVisibleColumn('sexo')) {
            transformedItem.sexo = i.solicitante?.persona?.sexo.codigo;
        }
        if (isVisibleColumn('sector')) {
            transformedItem.sector = i.sector;
        }
        if (isVisibleColumn('sede')) {
            transformedItem.sede = i.sede?.nombre;
        }
        if (isVisibleColumn('seleccionado')) {
            transformedItem.seleccionado = i.seleccionado ? t('aspirante._seleccionado_') : t('aspirante._no_seleccionado_');
        }
        if (isVisibleColumn('carrera')) {
            transformedItem.carrera = i.solicitante?.convocatoria_aspirante[0].solicitud_carrera_sede.carrera_sede.carrera.nombreCompleto;
        }
        return transformedItem;
    }),
);

const itemsExportar = ref([]);

const conf = ref({
    titulo: t('reporte._ingreso_aspirantes_'),
    subtitulo1: t('convocatoria._convocatoria_') + ': ' + props.convocatoria.nombreCompleto,
    subtitulo2: props.sedeSeleccion && props.sedeSeleccion.length === 1 ? t('sede._sede_') + ': ' + props.sedeSeleccion[0].nombre : '',
    subtitulo3:
        props.carreraSeleccion && props.carreraSeleccion.length === 1 ? t('carrera._singular_') + ': ' + props.carreraSeleccion[0].nombre : '',
});

const loadReport = async () => {
    error.value = null;

    // Si hay una carga previa, cancelarla
    if (controller) controller.abort();
    controller = new AbortController();

    loading.value = true;

    try {
        const payload = {
            convocatoria: props.convocatoria.id,
            estadoSeleccion: props.estadoSeleccion.map((e) => e.id),
            sedeSeleccion: props.sedeSeleccion.map((s) => s.id),
            carreraSeleccion: props.carreraSeleccion.map((c) => c.id),
        };

        //Obtener los datos desde el servidor
        const response = await axios.post(route('ingreso-reportes-aspirantes-data'), payload, { signal: controller.signal });

        items.value = response.data.aspirantes;
    } catch (err: any) {
        if (axios.isCancel(err)) {
            console.log('Petición cancelada');
        } else {
            error.value = t('reporte._error_al_cargar_');
            console.error(err);
        }
    } finally {
        loading.value = false;
    }
};

watch(
    () => props.step,
    (step) => {
        if (step !== 2) return;
        loadReport();
    },
    { immediate: true },
);

const exportarExcel = () => {
    const hoja = XLSX.utils.json_to_sheet(itemsExportar.value.map((item, index) => ({ '#': index + 1, ...item })));
    const libro = XLSX.utils.book_new();

    XLSX.utils.book_append_sheet(libro, hoja, 'Aspirantes');

    XLSX.writeFile(libro, 'Aspirantes.xlsx');
};

const exportarPDF = (orientacion = 'portrait') => {
    const doc = new jsPDF({ unit: 'pt', format: 'letter', orientation: orientacion });

    const totalPagesExp = '{total_pages_count_string}';
    const fecha = new Date();
    const opciones = { day: '2-digit', month: '2-digit', year: 'numeric' };
    const fechaFormateada = new Intl.DateTimeFormat('es-ES', opciones).format(fecha);

    const fechaHora = fechaFormateada + ' ' + fecha.toLocaleTimeString();

    // Encabezado
    doc.setFontSize(16);
    doc.text(conf.value.titulo, 40, 40);

    doc.setFontSize(11);
    if (conf.value.subtitulo1) doc.text(conf.value.subtitulo1, 40, 60);
    if (conf.value.subtitulo2) doc.text(conf.value.subtitulo2, 40, 75);
    if (conf.value.subtitulo3) doc.text(conf.value.subtitulo3, 40, 90);

    // Preparar los datos de la tabla
    const datos = itemsExportar.value.map((item, index) => ({ ...item, index: index + 1 }));

    const columnas = visibleHeaders.value.map((h) => ({
        header: h.title,
        dataKey: h.key,
    }));

    // Construir la tabla
    autoTable(doc, {
        startY: 100, // debajo del encabezado
        columns: columnas,
        body: datos,
        theme: 'grid',
        styles: {
            fontSize: 10,
            cellPadding: 4,
        },
        headStyles: {
            fillColor: [30, 144, 255],
            textColor: 255,
        },
        //para dibujar el pie de página
        didDrawPage: (data) => {
            const pageSize = doc.internal.pageSize;
            const pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
            const pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth();

            const pageNumber = doc.internal.getNumberOfPages();

            doc.setFontSize(9);

            // Izquierda: fecha y hora
            doc.text(fechaHora, 40, pageHeight - 20);

            // Centro: número de página
            doc.text(`${t('reporte._pagina_')} ${pageNumber} ${t('reporte._de_')} ${totalPagesExp}`, 20 + pageWidth / 2, pageHeight - 20, {
                align: 'center',
            });
        },
    });

    // Reemplaza {total_pages_count_string} con el número real
    if (typeof doc.putTotalPages === 'function') {
        doc.putTotalPages(totalPagesExp);
    }

    doc.save('aspirantes.pdf');
};
</script>

<template>
    <!-- Loader -->
    <div class="d-flex my-4 justify-center" v-if="loading">
        <v-progress-circular indeterminate color="primary" size="50" width="5" />
    </div>

    <!-- Error -->
    <v-alert v-if="error" type="error" class="mb-4" border="start" prominent variant="outlined">
        {{ error }}
    </v-alert>

    <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
        <v-tab :value="1">
            <v-icon icon="mdi-folder-table-outline" size="24" class="mr-2"></v-icon>
            {{ $t('reporte._singular_') }}
        </v-tab>
        <v-tab :value="2">
            <v-icon icon="mdi-table-pivot" size="24" class="mr-2"></v-icon>
            {{ $t('reporte._tabla_dinamica_') }}
        </v-tab>
    </v-tabs>

    <v-tabs-window v-model="tab">
        <v-tabs-window-item :value="1">
            <v-container fluid>
                <v-toolbar color="surface">
                    <template v-slot:prepend>
                        <v-btn @click="$emit('update:step', 1)" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-arrow-left-bold">
                            <template v-slot:prepend>
                                <v-icon color="success"></v-icon>
                            </template>
                            {{ t('_atras_') }}
                        </v-btn>
                    </template>
                    <template v-slot:append>
                        <v-menu>
                            <template #activator="{ props }">
                                <v-btn v-bind="props" size="small" color="primary" stacked prepend-icon="mdi-format-columns">{{
                                    t('reporte._columnas_')
                                }}</v-btn>
                            </template>

                            <v-list>
                                <v-list-item v-for="h in headers" :key="h.key">
                                    <v-checkbox v-model="h.visible" :label="h.title" hide-details />
                                </v-list-item>
                            </v-list>
                        </v-menu>
                        <v-menu>
                            <template #activator="{ props }">
                                <v-btn v-bind="props" size="small" color="primary" stacked prepend-icon="mdi-table-arrow-right">{{
                                    t('reporte._exportar_')
                                }}</v-btn>
                            </template>

                            <v-list>
                                <v-list-item @click="exportarExcel">
                                    <template v-slot:prepend>
                                        <v-icon icon="mdi-file-excel" color="primary"></v-icon>
                                    </template>

                                    <v-list-item-title>Excel</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="exportarPDF('portrait')">
                                    <template v-slot:prepend>
                                        <v-icon icon="mdi-drag-vertical" color="primary"></v-icon>
                                    </template>
                                    <v-list-item-title>{{ t('reporte._pdf_vertical_') }}</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="exportarPDF('landscape')">
                                    <template v-slot:prepend>
                                        <v-icon icon="mdi-drag-horizontal" color="primary"></v-icon>
                                    </template>
                                    <v-list-item-title>{{ t('reporte._pdf_horizontal_') }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </template>
                </v-toolbar>

                <v-card v-if="!loading">
                    <v-card-title> {{ conf.titulo }} </v-card-title>
                    <v-card-subtitle v-if="conf.subtitulo1"> {{ conf.subtitulo1 }} </v-card-subtitle>
                    <v-card-subtitle v-if="conf.subtitulo2"> {{ conf.subtitulo2 }} </v-card-subtitle>
                    <v-card-subtitle v-if="conf.subtitulo3"> {{ conf.subtitulo3 }} </v-card-subtitle>
                    <v-card-text>
                        <v-data-table
                            :headers="visibleHeaders"
                            :items="itemsTransformados"
                            :sort-by="sortBy"
                            density="compact"
                            border="primary thin"
                            class="w-100"
                            multi-sort
                            hover
                            striped="odd"
                            :items-per-page="-1"
                            hide-default-footer
                        >
                            <template #body="{ items }">
                                <span v-if="itemsExportar !== items" class="d-none">{{ itemsExportar = items }}</span>
                                <tr v-for="(row, index) in items" :key="row.id">
                                    <td>{{ index + 1 }}</td>
                                    <td v-for="field in row" :key="field">{{ field }}</td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-tabs-window-item>
    </v-tabs-window>
    <v-tabs-window v-model="tab">
        <v-tabs-window-item :value="2">
            <v-container fluid><TablaDinamica :items="itemsTransformados" /></v-container>
        </v-tabs-window-item>
    </v-tabs-window>
</template>
