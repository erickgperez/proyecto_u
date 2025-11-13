<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import { usePermissions } from '@/composables/usePermissions';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { rules, mensajeError, mensajeExito } = useFunciones();
const { hasPermission } = usePermissions();

const props = defineProps({
    convocatorias: Array,
});

const formRef = ref<VForm | null>(null);
interface FormData {
    convocatoria: number | null;
    tipoNotificacion: string;
}

const formData = ref<FormData>({
    convocatoria: null,
    tipoNotificacion: 'no-enviados',
});
const loading = ref(false);

const tipoNotificacion = [
    { value: 'no-enviados', title: t('convocatoria._notificar_no_enviados__') },
    { value: 'reenviar', title: t('convocatoria._notificar_reenviar_') },
];

const submitForm = async () => {
    const { valid } = await formRef.value!.validate();
    loading.value = true;
    if (valid) {
        try {
            const resp = await axios.post(route('ingreso-convocatoria-seleccion-notificar'), formData.value);
            if (resp.data.status == 'ok') {
                Swal.fire({
                    title: t('_exito_'),
                    text: t('convocatoria._cantidad_notificaciones_envidadas_') + ': ' + resp.data.enviados,
                    icon: 'success',
                    position: 'top-end',
                    confirmButtonColor: '#D7E1EE',
                });
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            const msj = axios.isAxiosError(error) ? error.response.data.message : t(error.message);
            mensajeError(t('convocatoria._notificaciones_no_enviadas_') + '. ' + msj);
        }
    }
    loading.value = false;
};
</script>

<template>
    <Head :title="$t('convocatoria._notificar_resultado_seleccion_')"></Head>
    <AppLayout
        :titulo="$t('convocatoria._notificar_resultado_seleccion_')"
        :subtitulo="$t('convocatoria._notificar_descripcion_')"
        icono="mdi-email-newsletter"
    >
        <v-sheet v-if="hasPermission('MENU_INGRESO_ASPIRANTES_SELECCION_NOTIFICAR')" class="elevation-12 pa-2 mx-auto rounded-xl" max-width="600">
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-select
                    :label="$t('convocatoria._convocatoria_')"
                    :rules="[rules.required]"
                    :items="props.convocatorias"
                    item-value="id"
                    item-title="descripcion"
                    v-model="formData.convocatoria"
                    prepend-icon="mdi-form-dropdown"
                    icon-color="deep-orange"
                ></v-select>
                <v-select
                    :label="$t('convocatoria._tipo_notificacion_')"
                    :rules="[rules.required]"
                    :items="tipoNotificacion"
                    v-model="formData.tipoNotificacion"
                    prepend-icon="mdi-form-dropdown"
                    icon-color="deep-orange"
                ></v-select>
                <div class="d-flex align-center justify-center">
                    <v-btn :loading="loading" class="mt-2" append-icon="mdi-upload" type="submit" rounded variant="tonal" color="blue-darken-4">{{
                        $t('_enviar_')
                    }}</v-btn>
                </div>
            </v-form>
        </v-sheet>
        <v-alert v-else border="top" type="warning" variant="outlined" prominent>
            {{ $t('_no_tiene_permiso_para_esta_accion_') }}
        </v-alert>
    </AppLayout>
</template>
