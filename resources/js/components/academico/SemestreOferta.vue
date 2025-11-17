<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

const props = defineProps(['item', 'accion']);

const items = ref([]);
const oferta = ref([]);
const active = ref([]);

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
                        <v-icon v-if="!item.children && item.ofertada" icon="mdi-check" color="success"></v-icon>
                        <v-icon v-if="!item.children && !item.ofertada" icon="mdi-close" color="error"></v-icon>
                    </template>
                </v-treeview>
            </v-col>

            <v-col class="d-flex text-center" cols="12" md="7">
                <v-card
                    class="text-h6 flex-1-1 d-flex justify-center"
                    v-if="selected && selected.tipo === 'unidad'"
                    color="surface-light"
                    height="100%"
                    flat
                    rounded
                >
                    <template v-slot:text>
                        <v-btn v-if="!selected.ofertada" prepend-icon="mdi-publish" stacked variant="outlined" color="primary">
                            {{ $t('semestre._ofertar_') }}
                        </v-btn>
                        <template v-else>
                            Hola
                            <!--<h3 class="text-h5">{{ active.name }}</h3>

                            <div class="text-medium-emphasis">{{ selected.email }}</div>

                            <div class="text-medium-emphasis font-weight-bold">{{ selected.username }}</div>

                            <v-divider class="my-4"></v-divider>

                            <v-text-field
                                :model-value="selected.company.name"
                                class="mx-auto mb-2"
                                density="compact"
                                max-width="250"
                                prefix="Company:"
                                variant="solo"
                                flat
                                hide-details
                                readonly
                            ></v-text-field>

                            <v-text-field
                                :model-value="selected.website"
                                class="mx-auto mb-2"
                                density="compact"
                                max-width="250"
                                prefix="Website:"
                                variant="solo"
                                flat
                                hide-details
                                readonly
                            ></v-text-field>

                            <v-text-field
                                :model-value="selected.phone"
                                class="mx-auto"
                                density="compact"
                                max-width="250"
                                prefix="Phone:"
                                variant="solo"
                                flat
                                hide-details
                                readonly
                            ></v-text-field>-->
                        </template>
                    </template>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
