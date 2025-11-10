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
const { mensajeExito, mensajeError } = useFunciones();

const date = useDate();

const getColor = (item: any, tipo: string): string => {
    let _color = '';
    const currentDate = new Date();

    // Eventos pasados
    if (date.isAfter(currentDate, date.date(item.fecha_fin))) {
        _color = 'grey-lighten-1';
    } else if (date.isAfter(currentDate, date.date(item.fecha_inicio)) && date.isBefore(currentDate, date.date(item.fecha_fin))) {
        //Eventos en curso
        _color = 'green-lighten-1';
    } else {
        _color = 'red-lighten-2';
    }
    return (tipo == '' ? '' : tipo + '-') + _color;
};

const loading = ref(false);
const formRef = ref<VForm | null>(null);
interface Evento {
    id: number | null;
    step: string;
    nombre: string;
    indicaciones: string;
    fecha_inicio: Date | null;
    fecha_fin: Date | null;
    icon: string;
}

const eventoVacio = ref<Evento>({
    id: null,
    step: '',
    nombre: '',
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
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            const msj = error instanceof Error ? t(error.message) : error.response.data.message;
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
                const resp = await axios.delete(route('general-actividad-delete', { id: item.id }));

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

onMounted(() => {
    axios
        .get(route('general-actividad-index', { id: props.idCalendario }))
        .then(function (response) {
            eventos.value = response.data.items;
        })
        .catch(function (error) {
            // handle error
            console.error('Error fetching data:', error);
        });
});
</script>

<template>
    <v-layout>
        <v-app-bar color="primary" class="bg-transparent" density="compact" rounded="t-lg">
            <v-toolbar-title>{{ $t('convocatoria._listado_actividades_') }}</v-toolbar-title>

            <template v-slot:append>
                <v-btn
                    icon="mdi-calendar-plus"
                    :title="$t('calendario._agregar_actividad_')"
                    @click="
                        formData = eventoVacio;
                        dialog = true;
                    "
                ></v-btn>
            </template>
        </v-app-bar>

        <v-main>
            <v-container>
                <v-timeline align="start" v-if="eventos.length > 0">
                    <v-timeline-item v-for="(item, i) in eventos" :key="i" :dot-color="getColor(item, '')" fill-dot :icon="item.icon">
                        <template v-slot:opposite>
                            <div :class="getColor(item, 'text')" class="headline font-weight-bold pt-1">{{ item.step }} : {{ item.nombre }}</div>
                        </template>
                        <v-card>
                            <v-card-title class="text-h6" :class="getColor(item, 'bg')">
                                {{ $t('_desde_') + ' ' }} {{ date.format(item.fecha_inicio, 'keyboardDate') }} {{ $t('_hasta_') + ' ' }}
                                {{ date.format(item.fecha_fin, 'keyboardDate') }}
                            </v-card-title>
                            <v-card-text class="text--primary bg-white">
                                <p v-html="item.indicaciones"></p>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn icon="mdi-delete-alert-outline" :title="$t('calendario._borrar_actividad_')" @click="remove(item)"></v-btn>
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
        </v-main>
    </v-layout>
    <v-dialog v-model="dialog" width="70%">
        <v-card prepend-icon="mdi-update" :title="$t('actividad._actividad_')">
            <template v-slot:text>
                <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                                prepend-icon="mdi-form-textbox"
                                v-model="formData.nombre"
                                :rules="[
                                    (v) => !!v || $t('_campo_requerido_'),
                                    (v) => !v || v.length <= 15 || $t('_longitud_maxima_') + ': 15 ' + $t('_caracteres_'),
                                ]"
                                counter="15"
                                :label="$t('_nombre_') + ' *'"
                            ></v-text-field>

                            <v-locale-provider locale="es">
                                <v-date-input
                                    clearable
                                    required
                                    v-model="formData.fecha_inicio"
                                    :rules="[(v) => !!v || $t('_fecha_requerida_')]"
                                    :label="$t('_fecha_inicio_') + ' *'"
                                ></v-date-input>
                            </v-locale-provider>
                            <v-locale-provider locale="es">
                                <v-date-input
                                    clearable
                                    required
                                    v-model="formData.fecha_fin"
                                    :rules="[(v) => !!v || $t('_fecha_requerida_')]"
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
