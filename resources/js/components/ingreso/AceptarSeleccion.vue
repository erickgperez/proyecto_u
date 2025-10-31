<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const emit = defineEmits(['form-saved']);

const props = defineProps(['solicitud']);

const today = new Date();
const fechaPublicacion = ref(new Date(props.solicitud.modelo.configuracion.fecha_publicacion_resultados));

async function submitForm() {}

onMounted(() => {
    /*axios
        .get(route('ingreso-aspirante-convocatoria-carrera', { id: props.aspirante.id }))
        .then(function (response) {
            convocatorias.value = response.data.convocatorias;
            carreras.value = response.data.carreras;
        })
        .catch(function (error) {
            console.error('Error fetching data:', error);
        });*/
});
</script>
<template>
    <v-container> {{ today > fechaPublicacion }} </v-container>
</template>
