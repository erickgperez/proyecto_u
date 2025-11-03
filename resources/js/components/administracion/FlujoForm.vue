<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { rules } = useFunciones();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    formRef.value!.reset();
}

interface FormData {
    id: number | null;
    codigo: string;
    nombre: string;
    activo: boolean;
    tipo_flujo_id: number | null;
}

const props = defineProps(['item', 'accion', 'tipos']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    activo: false,
    tipo_flujo_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.post(route('procesos-proceso-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);
                Swal.fire({
                    title: t('_exito_'),
                    text: t('_datos_subidos_correctamente_'),
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    toast: true,
                });
            } else {
                hasError.value = true;
                message.value = t(resp.data.message);
            }
        } catch (error: any) {
            hasError.value = true;
            message.value = t('_no_se_pudo_guardar_formulario_');
            console.log(error);
        }

        if (hasError.value) {
            Swal.fire({
                title: t('_error_'),
                text: message.value,
                icon: 'error',
                confirmButtonColor: '#D7E1EE',
            });
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();
    if (props.accion === 'edit') {
        formData.value = { ...props.item };
    }
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('flujo._editar_') : $t('flujo._crear_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[rules.required, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('flujo._codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[rules.required, rules.maxLength(255)]"
                            counter="255"
                            :label="$t('flujo._nombre_') + ' *'"
                        ></v-text-field>

                        <v-checkbox
                            icon-color="deep-orange"
                            class="ml-8"
                            :model-value="formData.activo"
                            color="primary"
                            :label="$t('flujo._activo_') + ' *'"
                        ></v-checkbox>

                        <v-autocomplete
                            clearable
                            icon-color="deep-orange"
                            :rules="[rules.required]"
                            :label="$t('flujo._tipo_') + ' *'"
                            :items="tipos"
                            v-model="formData.tipo_flujo_id"
                            item-title="codigo"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>
                    </v-col>

                    <v-col cols="12" align="right">
                        <v-btn :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                            {{ $t('_guardar_') }}
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </template>
    </v-card>
</template>
