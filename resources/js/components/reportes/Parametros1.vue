<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import { VForm } from 'vuetify/components';

const { rules } = useFunciones();

const emit = defineEmits(['update:convocatoria', 'update:estado-seleccion', 'update:sede-seleccion', 'update:carrera-seleccion']);

const convocatoria = ref(null);
const estadoSeleccion = ref(null);
const sedeSeleccion = ref([]);
const carreraSeleccion = ref([]);

const estados = ref([
    { id: 1, title: 'Seleccionado' },
    { id: 0, title: 'No seleccionado' },
]);

const convocatorias = ref([]);
const sedes = ref([]);
const carreras = ref([]);
const formRef = ref<VForm | null>(null);

onMounted(() => {
    //Obtener desde el servidor los valores de convocatorias, sedes y carreras
    axios.get(route('reportes-parametros1')).then((response) => {
        convocatorias.value = response.data.convocatorias;
        sedes.value = response.data.sedes;
        carreras.value = response.data.carreras;
    });
});

watch(convocatoria, (newVal) => {
    emit('update:convocatoria', newVal);
});

watch(estadoSeleccion, (newVal) => {
    emit('update:estado-seleccion', newVal);
});

watch(sedeSeleccion, (newVal) => {
    emit('update:sede-seleccion', newVal);
});

watch(carreraSeleccion, (newVal) => {
    emit('update:carrera-seleccion', newVal);
});

defineExpose({ formRef });
</script>

<template>
    <v-container>
        <v-form fast-fail ref="formRef">
            <v-row>
                <v-col cols="12" md="6">
                    <v-select
                        prepend-icon="mdi-form-dropdown"
                        icon-color="deep-orange"
                        :rules="[rules.required]"
                        v-model="convocatoria"
                        :items="convocatorias"
                        item-value="id"
                        item-title="nombreCompleto"
                        item-subtitle="descripcion"
                        :label="$t('convocatoria._convocatoria_')"
                        return-object
                    ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                    <v-select
                        prepend-icon="mdi-form-dropdown"
                        icon-color="deep-orange"
                        :rules="[rules.required]"
                        v-model="estadoSeleccion"
                        :items="estados"
                        item-value="id"
                        item-title="title"
                        :label="$t('reporte._estado_seleccion_')"
                        multiple
                        clearable
                        chips
                        return-object
                    ></v-select>
                </v-col>
                <v-col cols="12">
                    <v-select
                        prepend-icon="mdi-form-dropdown"
                        v-model="sedeSeleccion"
                        :items="sedes"
                        item-value="id"
                        item-title="nombre"
                        :label="$t('sede._sede_')"
                        chips
                        clearable
                        multiple
                        return-object
                        :hint="$t('reporte._sede_seleccion_hint_')"
                        persistent-hint
                    ></v-select>
                </v-col>
                <v-col cols="12">
                    <v-select
                        prepend-icon="mdi-form-dropdown"
                        v-model="carreraSeleccion"
                        :items="carreras"
                        item-value="id"
                        item-title="nombreCompleto"
                        :label="$t('carrera._singular_')"
                        multiple
                        chips
                        clearable
                        return-object
                        :hint="$t('reporte._carrera_seleccion_hint_')"
                        persistent-hint
                    ></v-select>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>
