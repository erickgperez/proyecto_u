<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
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

const allSedesSelected = computed(() => {
    return sedeSeleccion.value?.length === sedes.value.length && sedes.value.length > 0;
});

const someSedesSelected = computed(() => {
    return sedeSeleccion.value?.length > 0 && sedeSeleccion.value?.length < sedes.value.length;
});

const toggleSelectAllSedes = () => {
    if (allSedesSelected.value) {
        sedeSeleccion.value = [];
    } else {
        sedeSeleccion.value = sedes.value.map((s) => s.id);
    }
};

const allCarrerasSelected = computed(() => {
    return carreraSeleccion.value?.length === carreras.value.length && carreras.value.length > 0;
});

const someCarrerasSelected = computed(() => {
    return carreraSeleccion.value?.length > 0 && carreraSeleccion.value?.length < carreras.value.length;
});

const toggleSelectAllCarreras = () => {
    if (allCarrerasSelected.value) {
        carreraSeleccion.value = [];
    } else {
        carreraSeleccion.value = carreras.value.map((c) => c.id);
    }
};
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
                        icon-color="deep-orange"
                        v-model="sedeSeleccion"
                        :items="sedes"
                        item-value="id"
                        item-title="nombre"
                        :label="$t('sede._sede_')"
                        chips
                        clearable
                        multiple
                        return-object
                    >
                        <template v-slot:prepend-item>
                            <v-list-item title="Seleccionar todo" @click="toggleSelectAllSedes">
                                <template v-slot:prepend>
                                    <v-checkbox-btn
                                        :model-value="allSedesSelected"
                                        :indeterminate="someSedesSelected && !allSedesSelected"
                                    ></v-checkbox-btn>
                                </template>
                            </v-list-item>
                            <v-divider class="mt-2"></v-divider>
                        </template>
                    </v-select>
                </v-col>
                <v-col cols="12">
                    <v-select
                        prepend-icon="mdi-form-dropdown"
                        icon-color="deep-orange"
                        v-model="carreraSeleccion"
                        :items="carreras"
                        item-value="id"
                        item-title="nombreCompleto"
                        :label="$t('carrera._singular_')"
                        multiple
                        chips
                        clearable
                        return-object
                    >
                        <template v-slot:prepend-item>
                            <v-list-item title="Seleccionar todo" @click="toggleSelectAllCarreras">
                                <template v-slot:prepend>
                                    <v-checkbox-btn
                                        :model-value="allCarrerasSelected"
                                        :indeterminate="someCarrerasSelected && !allCarrerasSelected"
                                    ></v-checkbox-btn>
                                </template>
                            </v-list-item>
                            <v-divider class="mt-2"></v-divider>
                        </template>
                    </v-select>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>
