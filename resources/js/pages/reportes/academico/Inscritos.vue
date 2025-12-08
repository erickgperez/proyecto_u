<script setup lang="ts">
import ParametrosSemestre from '@/components/reportes/ParametrosSemestre.vue';
import InscritosDatos from '@/components/reportes/academico/InscritosDatos.vue';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const step = ref(1);
const { hasPermission } = usePermissions();
const items = [t('reporte._parametros_'), t('reporte._informe_')];

const semestre = ref(null);
const sedeSeleccion = ref([]);
const carreraSeleccion = ref([]);
const unidadAcademicaSeleccion = ref([]);

const parametrosRef = ref(null);

onMounted(() => {
    step.value = 1;
});

const config = ref({
    titulo1: t('reporte._inscritos_'),
    titulo2: t('reporte._inscritos_'),
    subtitulo: t('reporte._inscritos_semestre_'),
    icono: 'mdi-playlist-check',
    permiso: 'MENU_ACADEMICO_REPORTES_ESTUDIANTES_INSCRITOS',
});

const validarParametros = async () => {
    const form = parametrosRef.value?.formRef;

    if (!form) return;

    const { valid } = await form.validate();

    if (valid) {
        step.value = 2;
    }
};
</script>

<template>
    <Head :title="config.titulo1"> </Head>
    <AppLayout :titulo="config.titulo2" :subtitulo="config.subtitulo" :icono="config.icono">
        <v-stepper
            v-model="step"
            :items="items"
            show-actions
            :prev-text="$t('reporte._anterior_')"
            :next-text="$t('reporte._continuar_')"
            alt-labels
            class="elevation-8 rounded-xl"
            v-if="hasPermission(config.permiso)"
        >
            <template v-slot:item.1>
                <h3 class="text-h6">{{ $t('reporte._parametros_descripcion_') }}</h3>

                <ParametrosSemestre
                    ref="parametrosRef"
                    @update:semestre="semestre = $event"
                    @update:sede-seleccion="sedeSeleccion = $event"
                    @update:carrera-seleccion="carreraSeleccion = $event"
                    @update:unidad-academica-seleccion="unidadAcademicaSeleccion = $event"
                />
            </template>

            <template v-slot:item.2>
                <InscritosDatos
                    :semestre="semestre"
                    :sedeSeleccion="sedeSeleccion"
                    :carreraSeleccion="carreraSeleccion"
                    :unidadAcademicaSeleccion="unidadAcademicaSeleccion"
                    :step="step"
                    @update:step="step = $event"
                />
            </template>
            <template v-slot:next
                ><v-col cols="auto">
                    <v-btn
                        @click="validarParametros"
                        :disabled="step != 1"
                        rounded
                        variant="tonal"
                        color="blue-darken-4"
                        append-icon="mdi-arrow-right-bold"
                    >
                        <template v-slot:append>
                            <v-icon color="success"></v-icon>
                        </template>
                        {{ t('_siguiente_') }}
                    </v-btn>
                </v-col></template
            >
            <template v-slot:prev>
                <v-row justify="start">
                    <v-col cols="auto">
                        <v-btn @click="step--" :disabled="step == 1" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-arrow-left-bold">
                            <template v-slot:prepend>
                                <v-icon color="success"></v-icon>
                            </template>
                            {{ t('_atras_') }}
                        </v-btn>
                    </v-col>
                </v-row>
            </template>
        </v-stepper>
    </AppLayout>
</template>
