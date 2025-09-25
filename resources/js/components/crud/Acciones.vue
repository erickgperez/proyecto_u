<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import axios from 'axios';
import Swal from 'sweetalert2';
import { computed, PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { hasPermission, hasAnyPermission } = usePermissions();

const { t } = useI18n();

interface Accion {
    permiso: string;
    title: string;
    text: string;
    emitAction: string;
    color: string;
    icon: string;
}

const emit = defineEmits(['action']);

const props = defineProps({
    acciones: {
        type: Array as PropType<Accion[]>,
        required: true,
        default: () => [],
    },
    selectedItemLabel: {
        type: String,
        required: true,
        default: () => '',
    },
    selectedItemId: {
        type: Number,
        required: true,
    },
    rutaBorrar: {
        type: String,
        required: true,
        default: () => '',
    },
});

const accionesCheckPermisos = computed(() => {
    return props.acciones.filter((accion: Accion) => hasPermission(accion.permiso));
});

function emitirAccion(accion: string) {
    if (accion === 'delete') {
        remove();
    } else {
        emit('action', accion);
    }
}

function remove() {
    const hasError = ref(false);
    const message = ref('');
    const messageLog = ref('');
    Swal.fire({
        title: t('_confirmar_borrar_registro_'),
        text: props.selectedItemLabel,
        showCancelButton: true,
        confirmButtonText: t('_borrar_'),
        cancelButtonText: t('_cancelar_'),
        confirmButtonColor: '#e5adac',
        cancelButtonColor: '#D7E1EE',
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const resp = await axios.delete(route(props.rutaBorrar, { id: props.selectedItemId }));

                if (resp.data.status == 'ok') {
                    Swal.fire({
                        title: t('_exito_'),
                        text: t('_registro_eliminado_correctamente_'),
                        icon: 'success',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        toast: true,
                    });

                    /*const index = localItems.value.findIndex((item) => item.id === selectedItem.value?.id);
                    localItems.value.splice(index, 1);

                    step.value = 1;*/
                    emit('action', 'delete');
                } else {
                    hasError.value = true;
                    message.value = t(resp.data.message);
                }
            } catch (error: any) {
                hasError.value = true;
                messageLog.value = error.response.data.message;
            }

            if (hasError.value) {
                console.log(messageLog.value);
                Swal.fire({
                    title: t('_error_'),
                    text: t('_no_se_pudo_eliminar_') + '. ' + message.value,
                    icon: 'error',
                    confirmButtonColor: '#D7E1EE',
                });
            }
        }
    });
}
</script>
<template>
    <v-card class="align-center justify-center">
        <v-card-title class="border-b-md">
            <h2 class="text-blue-darken-3">{{ props.selectedItemLabel }}</h2>
        </v-card-title>
        <v-row dense>
            <v-col cols="12" md="12" class="pt-6">
                <span class="text-h6">
                    <span>{{ $t('_elija_accion_realizar_') }}</span>
                </span>
            </v-col>

            <v-col cols="12" md="6" v-for="accion in accionesCheckPermisos" :key="accion.emitAction">
                <!-- <v-card
                    class="ma-2"
                    variant="outlined"
                    color="indigo"
                    hover
                    @click="emitirAccion(accion.emitAction)"
                    :title="accion.title"
                    :text="accion.text"
                >
                    <template v-slot:prepend>
                        <v-avatar :color="accion.avatarColor">
                            <v-icon :icon="accion.icon" size="x-large"></v-icon>
                        </v-avatar>
                    </template>
                </v-card>-->
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        class="ma-2"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        @click="emitirAccion(accion.emitAction)"
                        prominent
                        :icon="accion.icon"
                        :color="accion.color"
                        :title="accion.title"
                        :text="accion.text"
                    >
                    </v-alert>
                </v-hover>
            </v-col>
        </v-row>
    </v-card>
</template>
<style scoped>
.on-hover-alert {
    background-color: #7195ad15 !important;
    transition: background-color 0.3s ease;
}
</style>
