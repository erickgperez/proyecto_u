<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
import { VForm } from 'vuetify/components';

const { rules } = useFunciones();

const emit = defineEmits(['update:semestre', 'update:sede-seleccion', 'update:carrera-seleccion', 'update:unidad-academica-seleccion']);

const semestre = ref(null);
const sedeSeleccion = ref([]);
const carreraSeleccion = ref([]);
const unidadAcademicaSeleccion = ref([]);

const semestres = ref([]);
const sedes = ref([]);
const carreras = ref([]);
const unidadesAcademicas = computed(() => {
    let unidades = [];
    if (carreraSeleccion.value) {
        for (const carrera of carreraSeleccion.value) {
            unidades.push(...carrera.unidades_academicas);
        }
    }
    return unidades;
});

const formRef = ref<VForm | null>(null);

onMounted(() => {
    //Obtener desde el servidor los valores a utilizar en el formulario de parámetros
    axios.get(route('reportes-parametros-semestre')).then((response) => {
        semestres.value = response.data.semestres;
        sedes.value = response.data.sedes;
        carreras.value = response.data.carreras;
    });
});

watch(semestre, (newVal) => {
    emit('update:semestre', newVal);
});

watch(sedeSeleccion, (newVal) => {
    emit('update:sede-seleccion', newVal);
});

watch(carreraSeleccion, (newVal) => {
    emit('update:carrera-seleccion', newVal);
});

watch(unidadAcademicaSeleccion, (newVal) => {
    emit('update:unidad-academica-seleccion', newVal);
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
                        v-model="semestre"
                        :items="semestres"
                        item-value="id"
                        item-title="nombre"
                        item-subtitle="descripcion"
                        :label="$t('semestre._singular_')"
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
                    >
                    </v-select>
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
                    >
                    </v-select>
                </v-col>
                <v-col cols="12">
                    <v-autocomplete
                        prepend-icon="mdi-form-dropdown"
                        v-model="unidadAcademicaSeleccion"
                        :items="unidadesAcademicas"
                        item-value="id"
                        item-title="nombreCompleto"
                        :label="$t('unidadAcademica._singular_')"
                        multiple
                        chips
                        clearable
                        return-object
                        :hint="$t('reporte._unidad_academica_seleccion_hint_')"
                        persistent-hint
                    >
                    </v-autocomplete>
                </v-col>
            </v-row>
        </v-form>
    </v-container>
</template>
