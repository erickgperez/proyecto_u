<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';
import { route } from 'ziggy-js';

const { mensajeExito } = useFunciones();
const { t } = useI18n();
const date = useDate();

const props = defineProps(['expediente']);

const localExpediente = ref(
    props.expediente ? props.expediente.map((item) => ({ ...item, isretiro: false })).filter((item) => item.estado.codigo == 'EC') : [],
);

//función para realizar el retiro de la asignatura
const retirarAsignatura = (item) => {
    console.log(item);
    axios
        .post(route('academico-retiro', { uuid: item.uuid }), {
            expediente: item.id,
        })
        .then((response) => {
            if (response.data.status == 'ok') {
                localExpediente.value = localExpediente.value.filter((item) => item.id != response.data.expediente.id);
                mensajeExito(t('inscripcion._retiro_aplicado_correctamente_'));
            }
        })
        .catch((error) => {
            console.log(error);
        });
};
</script>
<template>
    <v-card class="mx-auto" rounded="xl" v-if="localExpediente.length > 0">
        <v-card-title>{{ $t('inscripcion._asignaturas_en_curso_') }}</v-card-title>
        <v-card-text>
            <v-data-iterator :items="localExpediente" item-value="id">
                <template v-slot:default="{ items, isExpanded, toggleExpand }">
                    <v-row>
                        <v-col v-for="item in items" :key="item.id" cols="12" md="6" sm="12">
                            <v-card variant="tonal" color="indigo-darken-3" rounded="xl" hover>
                                <v-card-title class="d-flex align-center text-subtitle-1">
                                    <h4>📚 {{ item.raw.nombre }}</h4>
                                </v-card-title>
                                <v-card-subtitle> {{ $t('_codigo_') }} {{ item.raw.codigo }} </v-card-subtitle>
                                <v-card-subtitle class="text-high-emphasis mb-1 opacity-100">
                                    {{ $t('inscripcion._matricula_') + ' ' + item.raw.matricula }}
                                </v-card-subtitle>
                                <v-card-subtitle class="text-high-emphasis mb-1 opacity-100">
                                    <span class="text-green-darken-2">
                                        {{ $t('inscripcion._calificacion_') + ' ' + +(item.raw.calificacion ? item.raw.calificacion : '0') }}
                                    </span>
                                </v-card-subtitle>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="error" text="Retirar" variant="text" @click="item.raw.isretiro = true"></v-btn>
                                </v-card-actions>

                                <div class="px-4">
                                    <v-switch
                                        :label="$t('inscripcion._ver_evaluaciones_')"
                                        :model-value="isExpanded(item)"
                                        @click="() => toggleExpand(item)"
                                        color="green-darken-2"
                                        base-color="primary"
                                    ></v-switch>
                                    <v-spacer></v-spacer>
                                </div>
                                <v-expand-transition>
                                    <div v-if="isExpanded(item)">
                                        <v-table
                                            v-if="
                                                item.raw.inscritos_pivot &&
                                                item.raw.inscritos_pivot.length > 0 &&
                                                item.raw.inscritos_pivot[0].calificacion &&
                                                item.raw.inscritos_pivot[0].calificacion.length > 0
                                            "
                                        >
                                            <thead>
                                                <tr>
                                                    <th class="text-center">{{ $t('evaluacion._singular_') }}</th>
                                                    <th class="text-center">{{ $t('evaluacion._porcentaje_') }}</th>
                                                    <th class="text-center">{{ $t('evaluacion._nota_') }}</th>
                                                    <th class="text-center">{{ $t('evaluacion._fecha_realizacion_') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="calificacion in item.raw.inscritos_pivot[0].calificacion" :key="calificacion.id">
                                                    <td>{{ calificacion.codigo }}</td>
                                                    <td class="text-right">{{ calificacion.porcentaje }}%</td>
                                                    <td class="text-right">{{ calificacion.calificacion }}</td>
                                                    <td class="text-right">{{ date.format(calificacion.fecha, 'keyboardDate') }}</td>
                                                </tr>
                                            </tbody>
                                        </v-table>
                                        <v-alert v-else type="info" variant="tonal">{{ $t('evaluacion._no_evaluaciones_registradas_') }}</v-alert>
                                    </div>
                                </v-expand-transition>
                                <v-expand-transition>
                                    <v-card
                                        v-if="item.raw.isretiro"
                                        variant="elevated"
                                        class="position-absolute w-100"
                                        height="100%"
                                        style="bottom: 0"
                                    >
                                        <v-card-text class="pb-0">
                                            <p class="text-h4 text-error d-flex align-center">
                                                <v-icon icon="mdi-alert-circle" class="mr-2"></v-icon>
                                                {{ $t('inscripcion._retiro_') }}
                                            </p>
                                            <span class="text-weight-bold text-error">{{ item.raw.nombre }}</span>
                                            <p class="text-medium-emphasis text-error">
                                                {{ $t('inscripcion._retiro_mensaje_') }}
                                            </p>
                                        </v-card-text>

                                        <v-card-actions class="pt-6">
                                            <v-btn
                                                color="primary"
                                                text="Cancelar"
                                                variant="tonal"
                                                class="rounded-xl"
                                                @click="item.raw.isretiro = false"
                                            ></v-btn>
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                color="error"
                                                text="Confirmar retiro"
                                                prepend-icon="mdi-alert-circle"
                                                variant="tonal"
                                                class="rounded-xl"
                                                @click="retirarAsignatura(item.raw)"
                                            ></v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-expand-transition>
                            </v-card>
                        </v-col>
                    </v-row>
                </template>
            </v-data-iterator>
        </v-card-text>
    </v-card>
</template>
