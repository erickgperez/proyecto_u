<script setup lang="ts">
import ExpedienteDatos from '@/components/academico/estudiante/ExpedienteDatos.vue';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const props = defineProps(['item']);

const carrerasSede = ref([]);
const carreraSede = ref(null);
const expediente = ref([]);
const mallaCurricular = ref([]);

onMounted(() => {
    //obtener del servidor las carreras en que está inscrito el estudiante
    axios.get(route('academico-estudiante-carreras', props.item.estudiante.uuid)).then((response) => {
        carrerasSede.value = response.data.carreras;
        if (carrerasSede.value.length === 1) {
            carreraSede.value = carrerasSede.value[0];
        }
    });
});

watch(carreraSede, () => {
    //obtener del servidor los datos del expediente
    axios
        .get(route('academico-estudiante-expediente-carrera-sede-json', { uuid: props.item.estudiante.uuid, id: carreraSede.value?.id }))
        .then((response) => {
            expediente.value = response.data.expediente;
            mallaCurricular.value = response.data.mallaCurricular;
        });
});
</script>

<template>
    <v-card class="rounded-t-xl">
        <v-toolbar class="bg-blue-grey-lighten-3 rounded-t-xl">
            <v-select
                class="mt-5 mr-4 ml-4"
                density="compact"
                v-model="carreraSede"
                :items="carrerasSede"
                item-title="titulo2"
                item-value="id"
                label="Carrera"
                return-object
            ></v-select>
        </v-toolbar>
        <v-card-title>{{ item.nombreCompleto }}</v-card-title>
        <v-card-text> </v-card-text>
        <ExpedienteDatos
            v-if="carreraSede"
            :expediente="expediente"
            :mallaCurricular="mallaCurricular"
            :estudiante="item.estudiante"
            :carreraSede="carreraSede"
        />
    </v-card>
</template>
