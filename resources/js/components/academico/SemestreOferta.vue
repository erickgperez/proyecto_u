<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

const props = defineProps(['item', 'accion']);

const items = ref([]);
const active = ref([]);
const detalleOferta = ref();

interface FormData {
    id: number | null;
    docente_id: number | null;
    docente: object | null;
    ofertada: boolean;
}

const formData = ref<FormData>({
    id: null,
    docente_id: null,
    docente: null,
    ofertada: true,
});

async function getOferta() {
    try {
        const resp = await axios.get(route('academico-semestre-oferta-index', { id: props.item.uuid }));
        if (resp.data.status == 'ok') {
            items.value = resp.data.items;
        } else {
            console.log(resp.data.message);
            throw new Error(resp.data.message);
        }
    } catch (error: any) {
        const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
        mensajeError(t('_error_') + '. ' + msj);
    }
}

async function getDetalleOferta(item) {
    try {
        const resp = await axios.get(route('academico-semestre-oferta-detalle', { id: props.item.uuid, idCarreraUnidadAcademica: item.uuid }));
        if (resp.data.status == 'ok') {
            detalleOferta.value = resp.data.detalleOferta;
        } else {
            console.log(resp.data.message);
            throw new Error(resp.data.message);
        }
    } catch (error: any) {
        const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
        mensajeError(t('_error_') + '. ' + msj);
    }
}

async function ofertar(item) {
    try {
        const resp = await axios.post(route('academico-semestre-ofertar', { id: props.item.uuid, idCarreraUnidadAcademica: item.uuid }));
        if (resp.data.status == 'ok') {
            item.ofertada = !item.ofertada;
            item.ofertaId = resp.data.ofertaId;
        } else {
            console.log(resp.data.message);
            throw new Error(resp.data.message);
        }
    } catch (error: any) {
        const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
        mensajeError(t('_error_') + '. ' + msj);
    }
}

async function guardarDetalle(detalle) {
    formData.value.id = detalle.id;
    formData.value.docente_id = formData.value.docente ? formData.value.docente.id : null;

    try {
        const resp = await axios.post(route('academico-semestre-oferta-detalle-save'), formData.value);
        if (resp.data.status == 'ok') {
            mensajeExito(t('_datos_subidos_correctamente_'));
            detalle.docente = formData.value.docente;
            detalle.ofertada = formData.value.ofertada;
            detalle.editando = !detalle.editando;
        } else {
            console.log(resp.data.message);
            throw new Error(resp.data.message);
        }
    } catch (error: any) {
        const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
        mensajeError(t('_error_') + '. ' + msj);
    }
}

const selected = computed(() => {
    if (!active.value.length > 0) return undefined;

    return active.value[0];
});

onMounted(() => {
    //reset();
    getOferta();
    //formData.value = { ...props.item };
});

watch(selected, (newVal) => {
    if (newVal && newVal.tipo === 'unidad') {
        if (newVal.ofertada) {
            //Recuperar el detalle de la oferta
            getDetalleOferta(newVal);
        }
    }
});
</script>
<template>
    <v-container fluid>
        <span class="text-h5">{{ $t('semestre._oferta_semestre_') }}: {{ item.codigo }} </span>
        <v-divider class="ma-2"></v-divider>
        <v-row justify="space-between" dense>
            <v-col cols="12" md="5">
                <v-treeview
                    :items="items"
                    v-model:activated="active"
                    density="compact"
                    item-title="nombreCompleto"
                    item-value="id"
                    activatable
                    border
                    fluid
                    rounded
                    return-object
                    open-on-click
                >
                    <template v-slot:prepend="{ item }">
                        <v-icon
                            size="large"
                            v-if="!item.children && item.ofertada"
                            icon="mdi-checkbox-marked-circle-outline"
                            color="success"
                            @click.stop="ofertar(item)"
                        ></v-icon>
                        <v-icon
                            size="small"
                            v-if="!item.children && !item.ofertada"
                            icon="mdi-close"
                            color="error"
                            @click.stop="ofertar(item)"
                        ></v-icon>
                    </template>
                </v-treeview>
            </v-col>

            <v-col class="d-flex text-center" cols="12" md="7">
                <v-container class="justify-center" v-if="selected && selected.tipo == 'unidad'">
                    <v-sheet v-if="!selected.ofertada">
                        <h3 class="text-h5">{{ $t('semestre._no_ofertada_') }}</h3>
                    </v-sheet>
                    <v-row v-else>
                        <v-col cols="12" md="6" v-for="detalle in detalleOferta" :key="detalle.id">
                            <v-card
                                rounded
                                :variant="detalle.editando ? 'tonal' : 'outlined'"
                                :elevation="detalle.editando ? 12 : 0"
                                color="indigo-lighten-1"
                            >
                                <template v-slot:title>
                                    <span :class="detalle.editando ? 'text-blue-accent-4' : ''">{{ detalle.sede }}</span>
                                </template>
                                <template v-slot:append>
                                    <v-icon
                                        v-if="!detalle.editando"
                                        :title="$t('_editar_')"
                                        color="indigo"
                                        icon="mdi-pencil"
                                        @click.stop="
                                            formData.docente = detalle.docente;
                                            formData.ofertada = detalle.ofertada;
                                            detalle.editando = !detalle.editando;
                                        "
                                    ></v-icon>
                                </template>

                                <template v-slot:text>
                                    <div class="text-medium-emphasis">
                                        <div v-if="!detalle.editando">
                                            <span v-if="detalle.ofertada">{{ $t('semestre._ofertada_') }}</span>
                                            <span v-else>{{ $t('semestre._no_ofertada_') }}</span>
                                            <v-icon v-if="detalle.ofertada" icon="mdi-check" color="success"></v-icon>
                                            <v-icon v-else icon="mdi-close" color="error"></v-icon>
                                        </div>
                                        <div v-else>
                                            <v-checkbox v-model="formData.ofertada" :label="$t('semestre._ofertada_')"></v-checkbox>
                                        </div>
                                    </div>

                                    <div class="text-medium-emphasis font-weight-bold">
                                        <div v-if="!detalle.editando">
                                            {{ $t('semestre._responsable_') }} : {{ detalle.docente?.persona?.nombreCompleto }}
                                        </div>
                                        <div v-else>
                                            <v-autocomplete
                                                :label="$t('semestre._responsable_')"
                                                :items="detalle.docentes"
                                                v-model="formData.docente"
                                                item-title="persona.nombreCompleto"
                                                item-value="id"
                                                chips
                                                density="compact"
                                                clearable
                                                return-object
                                            ></v-autocomplete>
                                        </div>
                                    </div>
                                    <!--<div class="text-medium-emphasis font-weight-bold">{{ $t('_cupo_') }} {{ detalle.cupo }}</div>-->
                                </template>
                                <v-card-actions v-if="detalle.editando">
                                    <v-icon :title="$t('_guardar_')" color="indigo" icon="mdi-content-save" @click.stop="guardarDetalle(detalle)">
                                    </v-icon>
                                    <v-spacer></v-spacer>
                                    <v-icon
                                        :title="$t('_cancelar_')"
                                        color="error"
                                        icon="mdi-close-circle-outline"
                                        @click.stop="detalle.editando = !detalle.editando"
                                    ></v-icon>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
        </v-row>
    </v-container>
</template>
