<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { reactive, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

interface Convocatoria {
    id: number;
    nombre: string;
    descripcion: string;
}

interface Props {
    convocatoria: Convocatoria;
}
const props = defineProps<Props>();
const formRef = ref<VForm | null>(null);
// Datos del formulario
const form = reactive({
    tipoInvitacion: null,
    tipoEnvio: null,
});
const loading = ref(false);

const { t } = useI18n();

const tipoEnvioRules: ((value: string) => true | string)[] = [(v) => !!v || 'Elija un tipo de envío'];
const tipoInvitacionRules: ((value: string) => true | string)[] = [(v) => !!v || 'Elija la forma de invitación'];

const opciones = [
    { value: 'todos', label: t('_todos_estudiantes_en_base_datos_') },
    { value: 'opciones', label: t('_solo_estudiantes_para_carreras_universitarias_') },
];
const opcionesTipoInvitacion = [
    { value: 'nuevo', label: t('_solo_invitaciones_no_enviadas_anteriormente_') },
    { value: 'reenvio', label: t('_reenviar_invitacion_') },
];

async function submitForm(): Promise<void> {
    const result = await formRef.value?.validate();

    if (result?.valid) {
        loading.value = true;
        axios
            .post(route('ingreso-bachillerato-candidatos-invitaciones'), {
                tipoEnvio: form.tipoEnvio,
                tipoInvitacion: form.tipoInvitacion,
                idConvocatoria: props.convocatoria.id,
            })
            .then(function (response) {
                Swal.fire({
                    title: t('_enviado_'),
                    position: 'top-end',
                    text: t('_invitaciones_enviadas_correctamente_'),
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500,
                });
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(function () {
                loading.value = false;
            });
    }
}
</script>
<template>
    <v-card class="mx-auto my-8" elevation="16">
        <v-card-item>
            <v-card-title> {{ $t('_enviar_invitaciones_bachilleres_') }} </v-card-title>

            <v-card-subtitle>
                {{ $t('_invitaciones_se_enviaran_si_tiene_correo_') }}
            </v-card-subtitle>
        </v-card-item>

        <v-card-text>
            <v-form @submit.prevent="submitForm" ref="formRef">
                <div>
                    <v-select
                        :label="$t('_enviar_invitaciones_a_')"
                        :rules="tipoEnvioRules"
                        :items="opciones"
                        :item-title="'label'"
                        v-model="form.tipoEnvio"
                    ></v-select>
                    <v-select
                        :label="$t('_tipo_invitacion_')"
                        :rules="tipoInvitacionRules"
                        :items="opcionesTipoInvitacion"
                        :item-title="'label'"
                        v-model="form.tipoInvitacion"
                    ></v-select>
                </div>
                <v-btn :loading="loading" rounded variant="tonal" color="blue-darken-4" append-icon="mdi-email-arrow-right-outline" type="submit">
                    {{ $t('_enviar_') }}
                </v-btn>
            </v-form>
            <v-overlay v-model="loading" persistent class="align-center justify-center" contained>
                <v-progress-circular color="primary" size="64" indeterminate></v-progress-circular>
            </v-overlay>
        </v-card-text>
    </v-card>
</template>
