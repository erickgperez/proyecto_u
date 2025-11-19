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

async function getOferta() {
    try {
        const resp = await axios.get(route('academico-semestre-oferta-index', { id: props.item.id }));
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
        const resp = await axios.get(route('academico-semestre-oferta-detalle', { id: props.item.id, idCarreraUnidadAcademica: item.id }));
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
        const resp = await axios.post(route('academico-semestre-ofertar', { id: props.item.id, idCarreraUnidadAcademica: item.id }));
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
        console.log(newVal);
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
                        <v-icon v-if="!item.children && item.ofertada" icon="mdi-check" color="success" @click.stop="ofertar(item)"></v-icon>
                        <v-icon v-if="!item.children && !item.ofertada" icon="mdi-close" color="error" @click.stop="ofertar(item)"></v-icon>
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
                            <v-card rounded>
                                <template v-slot:text>
                                    <h3 class="text-h5">{{ detalle.sede }}</h3>

                                    <div class="text-medium-emphasis">
                                        <span v-if="detalle.ofertada">{{ $t('semestre._ofertada_') }}</span>
                                        <span v-else>{{ $t('semestre._no_ofertada_') }}</span>
                                        <v-icon v-if="detalle.ofertada" icon="mdi-check" color="success"></v-icon>
                                        <v-icon v-else icon="mdi-close" color="error"></v-icon>
                                    </div>

                                    <div class="text-medium-emphasis font-weight-bold">{{ $t('_cupo_') }} {{ detalle.cupo }}</div>
                                </template>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
        </v-row>
    </v-container>
</template>
