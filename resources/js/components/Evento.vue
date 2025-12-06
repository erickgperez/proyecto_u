<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import {
    Bold,
    BulletList,
    Color,
    Document,
    //FontSize,
    // necessary extensions
    //Heading,
    Image,
    Italic,
    OrderedList,
    Paragraph,
    Strike,
    Text,
    Underline,
} from 'element-tiptap';
import Swal from 'sweetalert2';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

const date = useDate();

const getColor = (item: any, tipo: string): string => {
    let _color = '';
    const currentDate = new Date();

    // Eventos pasados
    if (date.isAfter(currentDate, date.date(item.fecha_fin))) {
        _color = 'grey-lighten-1';
    } else if (date.isAfter(currentDate, date.date(item.fecha_inicio)) && date.isBefore(currentDate, date.date(item.fecha_fin))) {
        //Eventos en curso
        _color = 'green-darken-2';
    } else {
        _color = 'red-lighten-2';
    }
    return (tipo == '' ? '' : tipo + '-') + _color;
};

const loading = ref(false);
const formRef = ref<VForm | null>(null);
interface Evento {
    id: number | null;
    uuid: string;
    step: string;
    nombre: string;
    tipo: object | null;
    tipo_evento_id: number | null;
    indicaciones: string;
    fecha_inicio: Date | null;
    fecha_fin: Date | null;
    icon: string;
}

const eventoVacio = ref<Evento>({
    id: null,
    uuid: '',
    step: '',
    nombre: '',
    tipo: null,
    tipo_evento_id: null,
    fecha_inicio: null,
    fecha_fin: null,
    indicaciones: '',
    icon: '',
});

interface Props {
    idCalendario: number | null;
}

const props = defineProps<Props>();

const eventos = ref<Evento[]>([]);
const tipoEvento = ref([]);
const calendario = ref(null);
const tab = ref(null);
const value = ref('');
const calendar = ref();
const dialog = ref(false);

const formData = ref<Evento>(eventoVacio.value);

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('general-actividad-save', { id: props.idCalendario }), formData.value);
            if (resp.data.status == 'ok') {
                eventos.value = resp.data.items;
                dialog.value = false;
                if (formRef.value) {
                    formRef.value.reset();
                }
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

function remove(item: Evento) {
    const hasError = ref(false);
    const message = ref('');
    const messageLog = ref('');
    Swal.fire({
        title: t('_confirmar_borrar_registro_'),
        text: item.nombre,
        showCancelButton: true,
        confirmButtonText: t('_borrar_'),
        cancelButtonText: t('_cancelar_'),
        confirmButtonColor: '#e5adac',
        cancelButtonColor: '#D7E1EE',
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const resp = await axios.delete(route('general-actividad-delete', { id: item.uuid }));

                if (resp.data.status == 'ok') {
                    eventos.value = resp.data.items;
                } else {
                    hasError.value = true;
                    message.value = t(resp.data.message);
                }
            } catch (error: any) {
                hasError.value = true;
                messageLog.value = error.response.data.message;
            }

            if (hasError.value) {
                console.log(messageLog.value);
                Swal.fire({
                    title: t('_error_'),
                    text: t('_no_se_pudo_eliminar_') + '. ' + message.value,
                    icon: 'error',
                    confirmButtonColor: '#D7E1EE',
                });
            }
        }
    });
}

// editor extensions
const extensions = [
    Document,
    Text,
    Paragraph,
    //Heading.configure({ levels: [1, 2, 3] }),
    //FontSize,
    Bold,
    Underline,
    Italic,
    Strike,
    BulletList,
    OrderedList,
    Color,
    Image.configure({ allowBase64: true }),
];

function getEventColor(event) {
    return getColor(event, '');
}
function prev() {
    calendar.value.prev();
}
function next() {
    calendar.value.next();
}

function addEvento() {
    formRef.value?.reset();
    formData.value = eventoVacio.value;

    dialog.value = true;
}

function showEvent(nativeEvent, { event }) {
    formData.value = event;
    dialog.value = true;
    nativeEvent.stopPropagation();
}

onMounted(() => {
    axios
        .get(route('general-actividad-index', { id: props.idCalendario }))
        .then(function (response) {
            eventos.value = response.data.items;
            calendario.value = response.data.calendario;
            tipoEvento.value = response.data.tipoEvento;
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});
</script>

<template>
    <v-layout>
        <v-app-bar class="bg-blue-grey-lighten-3 ma-0 pa-0">
            <v-toolbar-title>{{ calendario?.descripcion }}</v-toolbar-title>

            <template v-slot:append>
                <v-btn icon="mdi-calendar-plus" color="primary" :title="$t('calendario._agregar_actividad_')" @click="addEvento"></v-btn>
            </template>
        </v-app-bar>

        <v-main>
            <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
                <v-tab value="1">{{ $t('calendario._linea_tiempo_') }}</v-tab>
                <v-tab value="2">{{ $t('calendario._calendario_') }}</v-tab>
            </v-tabs>

            <v-tabs-window v-model="tab">
                <v-tabs-window-item value="1">
                    <v-container fluid>
                        <v-timeline align="start" v-if="eventos.length > 0">
                            <v-timeline-item v-for="(item, i) in eventos" :key="i" :dot-color="getColor(item, '')" fill-dot :icon="item.icon">
                                <template v-slot:opposite>
                                    <div :class="getColor(item, 'text')" class="headline font-weight-bold pt-1">
                                        {{ item.step }} : {{ item.tipo.codigo }}
                                    </div>
                                </template>
                                <v-card>
                                    <v-card-title class="text-h6" :class="getColor(item, 'bg')">
                                        {{ $t('_desde_') + ' ' }} {{ date.format(item.fecha_inicio, 'keyboardDate') }} {{ $t('_hasta_') + ' ' }}
                                        {{ date.format(item.fecha_fin, 'keyboardDate') }}
                                    </v-card-title>
                                    <v-card-text class="text--primary bg-white">
                                        <p>{{ item.nombre ?? item.tipo.descripcion }}</p>
                                        <p v-html="item.indicaciones"></p>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn
                                            icon="mdi-delete-alert-outline"
                                            :title="$t('calendario._borrar_actividad_')"
                                            @click="remove(item)"
                                        ></v-btn>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            icon="mdi-calendar-edit"
                                            :title="$t('calendario._editar_actividad_')"
                                            @click="
                                                formData = item;
                                                dialog = true;
                                            "
                                        ></v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-timeline-item>
                        </v-timeline>
                    </v-container>
                </v-tabs-window-item>
                <v-tabs-window-item value="2">
                    <v-row>
                        <v-col cols="12" md="2"></v-col>
                        <v-col cols="12" md="8">
                            <v-sheet>
                                <v-toolbar flat>
                                    <v-btn class="ma-2" variant="text" icon @click="prev">
                                        <v-icon>mdi-chevron-left</v-icon>
                                    </v-btn>
                                    <v-toolbar-title v-if="calendar">
                                        {{ calendar.title }}
                                    </v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <v-btn class="ma-2" variant="text" icon @click="next">
                                        <v-icon>mdi-chevron-right</v-icon>
                                    </v-btn>
                                </v-toolbar>
                            </v-sheet>
                            <v-sheet>
                                <v-calendar
                                    v-model="value"
                                    ref="calendar"
                                    type="month"
                                    :event-color="getEventColor"
                                    :events="eventos"
                                    event-overlap-mode="stack"
                                    event-overlap-threshold="2"
                                    event-start="fecha_inicio"
                                    event-end="fecha_fin"
                                    @click:event="showEvent"
                                ></v-calendar>
                            </v-sheet>
                        </v-col>
                        <v-col cols="12" md="2"></v-col>
                    </v-row>
                </v-tabs-window-item>
            </v-tabs-window>
            <v-container rounded="a-xl"> </v-container>
        </v-main>
    </v-layout>
    <v-dialog v-model="dialog" width="50%">
        <v-card prepend-icon="mdi-update" :title="$t('actividad._actividad_')">
            <template v-slot:text>
                <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                    <v-row>
                        <v-col cols="12">
                            <v-select
                                icon-color="deep-orange"
                                :rules="[rules.required]"
                                :label="$t('tipoEvento._singular_') + ' *'"
                                :items="tipoEvento"
                                v-model="formData.tipo_evento_id"
                                item-title="descripcion"
                                item-value="id"
                                prepend-icon="mdi-form-dropdown"
                            ></v-select>
                            <v-text-field
                                prepend-icon="mdi-form-textbox"
                                v-model="formData.nombre"
                                :rules="[rules.maxLength(100)]"
                                counter="15"
                                :label="$t('_nombre_')"
                            ></v-text-field>

                            <v-locale-provider locale="es">
                                <v-date-input
                                    clearable
                                    icon-color="deep-orange"
                                    required
                                    v-model="formData.fecha_inicio"
                                    :rules="[rules.required]"
                                    :label="$t('_fecha_inicio_') + ' *'"
                                ></v-date-input>
                            </v-locale-provider>
                            <v-locale-provider locale="es">
                                <v-date-input
                                    clearable
                                    icon-color="deep-orange"
                                    required
                                    v-model="formData.fecha_fin"
                                    :rules="[rules.required]"
                                    :label="$t('_fecha_fin_') + ' *'"
                                ></v-date-input>
                            </v-locale-provider>
                        </v-col>
                        <v-col cols="12" class="pl-15">
                            {{ $t('_indicaciones_') }}
                            <el-tiptap v-model:content="formData.indicaciones" :extensions="extensions" />
                        </v-col>

                        <v-col cols="12">
                            <v-btn :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                                {{ $t('_guardar_') }}
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </template>
            <template v-slot:actions>
                <v-btn :text="$t('_cancelar_')" rounded variant="tonal" @click="dialog = false"></v-btn>
            </template>
        </v-card>
    </v-dialog>
</template>
