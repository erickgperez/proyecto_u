<script setup lang="ts">
import { CarreraSede, Convocatoria, Sede, Solicitud } from '@/types/tipos';
import axios from 'axios';
import { computed, ref, watch } from 'vue';

const props = defineProps(['convocatorias', 'drawer']);

const emit = defineEmits([
    'seleccionAutomatica',
    'changeDrawer',
    'carrerasSede',
    'solicitudes',
    'infoSede',
    'changeConvocatoria',
    'changeSede',
    'changeTipoSeleccion',
]);

function itemProps(item: Convocatoria) {
    return {
        id: item.id,
        title: item.nombre,
        subtitle: item.descripcion,
    };
}

const loading = ref(false);
const convocatoria = ref<Convocatoria | null>(null);
const sede = ref<Sede | null>(null);
const localDrawer = props.drawer;
const tipoSeleccion = ref(null);
const solicitudes = ref<Solicitud[]>([]);
const ejecutandoSeleccionAutomatica = ref(false);

const sedes = computed(() => {
    const sedes_ = convocatoria.value?.carreras_sedes.map((cs: CarreraSede) => cs.sede);
    return sedes_?.filter((obj, index, self) => index === self.findIndex((t) => t.id === obj.id));
});

function cargarSolicitudes() {
    if (convocatoria.value != null && sede.value != null) {
        loading.value = true;
        axios
            .get(route('ingreso-convocatoria-solicitudes', { id: convocatoria.value.id, idSede: sede.value.id }))
            .then(function (response) {
                emit('carrerasSede', response.data.ofertaSede);
                emit('solicitudes', response.data.solicitudes);
                emit('infoSede', response.data.infoSede);
            })
            .catch(function (error) {
                // handle error
                console.error('Error fetching data:', error);
            })
            .finally(function () {
                loading.value = false;
            });
    }
}

watch(localDrawer, () => {
    emit('changeDrawer');
});

watch(convocatoria, () => {
    emit('changeConvocatoria', convocatoria.value);
});

watch(sede, () => {
    emit('changeSede', sede.value);
});

watch(tipoSeleccion, () => {
    emit('changeTipoSeleccion', tipoSeleccion.value);
});
</script>
<template>
    <v-navigation-drawer location="right" v-model="props.drawer" color="blue-grey-lighten-5" :width="400">
        <v-row>
            <v-col cols="12">
                <v-list>
                    <v-list-item class="font-weight-black text-primary">
                        <template v-slot:append>
                            <v-btn
                                icon="mdi-close"
                                :title="$t('_cerrar_parametros_')"
                                color="primary"
                                size="small"
                                variant="text"
                                @click.stop="emit('changeDrawer')"
                            ></v-btn>
                        </template>
                        <template v-slot:prepend>
                            <v-icon icon="mdi-application-cog" size="small" variant="text"></v-icon>
                        </template>
                        {{ $t('_parametros_') }}
                    </v-list-item>
                </v-list>

                <v-divider></v-divider>
                <v-select
                    clearable
                    v-model="convocatoria"
                    :label="$t('convocatoria._convocatoria_')"
                    :items="props.convocatorias"
                    :item-props="itemProps"
                ></v-select>
                <v-select
                    clearable
                    v-model="sede"
                    :label="$t('sede._sede_')"
                    :items="sedes"
                    item-title="nombre"
                    item-id="id"
                    return-object
                ></v-select>
                <v-row justify="center">
                    <v-btn
                        :loading="loading"
                        color="primary"
                        rounded="xl"
                        variant="elevated"
                        prepend-icon="mdi-reload"
                        @click="cargarSolicitudes"
                        :disabled="convocatoria == null || sede == null"
                        >{{ $t('_cargar_') }}</v-btn
                    >
                </v-row>
            </v-col>
        </v-row>
        <v-row>
            <v-col class="mt-6">
                <v-expansion-panels v-if="solicitudes.length > 0">
                    <v-expansion-panel :title="$t('convocatoria._seleccion_automatica_')">
                        <v-expansion-panel-text>
                            <v-select
                                clearable
                                v-model="tipoSeleccion"
                                :label="$t('convocatoria._tipo_seleccion_')"
                                :items="[
                                    { id: 1, title: 'Nota y primera opción' },
                                    { id: 2, title: 'Nota, cualquier opción' },
                                ]"
                                item-title="title"
                                item-value="id"
                                return-object
                            ></v-select>

                            <span v-if="tipoSeleccion">
                                <span v-if="tipoSeleccion.id == 1"> {{ $t('convocatoria._seleccion_tipo1_') }}</span>
                                <span v-else> {{ $t('convocatoria._seleccion_tipo2_') }}</span>
                            </span>

                            <v-row justify="center" class="mt-6">
                                <v-btn
                                    color="success"
                                    :loading="ejecutandoSeleccionAutomatica"
                                    rounded="xl"
                                    variant="elevated"
                                    prepend-icon="mdi-play-speed"
                                    @click="emit('seleccionAutomatica')"
                                    :disabled="tipoSeleccion == null"
                                    >{{ $t('_ejecutar_') }}</v-btn
                                >
                            </v-row>
                        </v-expansion-panel-text>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-col>
        </v-row>
    </v-navigation-drawer>
</template>
