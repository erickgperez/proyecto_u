<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { CarreraSede, Estudiante, Expediente } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const tab = ref(null);

interface FormData {
    carga_inscrita: Array<[]>;
}

const formData = ref<FormData>({
    carga_inscrita: [],
});

const props = defineProps({
    expediente: {
        type: Array as PropType<Expediente[]>,
        required: true,
        default: () => [],
    },
    estudiante: {
        type: Object as PropType<Estudiante>,
        required: true,
    },
    carreraSede: {
        type: Object as PropType<CarreraSede>,
        required: true,
    },
});

const localExpediente = ref<Expediente[]>(props.expediente);
</script>

<template>
    <Head :title="$t('expediente._singular_')"></Head>
    <AppLayout :titulo="$t('expediente._singular_')" :subtitulo="$t('expediente._titulo_descripcion_')" icono="mdi-folder-table-outline">
        <v-card>
            <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4">
                <v-tab :value="1">{{ $t('expediente._singular_') }}</v-tab>
                <v-tab :value="2">{{ $t('mallaCurricular._singular_') }}</v-tab>
            </v-tabs>

            <v-tabs-window v-model="tab">
                <v-tabs-window-item :value="1">
                    <v-container fluid>
                        <v-table striped="even">
                            <thead>
                                <tr>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Calories</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in localExpediente" :key="item.id">
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.calories }}</td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-container>
                </v-tabs-window-item>
                <v-tabs-window-item :value="2">
                    <v-container fluid>
                        <v-row>
                            <v-col cols="12" md="4"> Malla</v-col>
                        </v-row>
                    </v-container>
                </v-tabs-window-item>
            </v-tabs-window>
        </v-card>
    </AppLayout>
</template>
