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
    primer_nombre: string;
    segundo_nombre: string;
    tercer_nombre: string;
    primer_apellido: string;
    segundo_apellido: string;
    tercer_apellido: string;
    fecha_nacimiento: Date | null;
    sexo_id: number | null;
}

const props = defineProps(['item', 'accion', 'sexos']);

const formData = ref<FormData>({
    id: null,
    primer_nombre: '',
    segundo_nombre: '',
    tercer_nombre: '',
    primer_apellido: '',
    segundo_apellido: '',
    tercer_apellido: '',
    fecha_nacimiento: null,
    sexo_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.post(route('administracion-persona-save'), formData.value);
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
    <v-card :title="`${isEditing ? $t('persona._editar_') : $t('persona._crear_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.primer_nombre"
                            :rules="[rules.required, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('persona._primer_nombre_') + ' *'"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.segundo_nombre"
                            :rules="[rules.maxLength(100)]"
                            counter="100"
                            :label="$t('persona._segundo_nombre_')"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.tercer_nombre"
                            :rules="[rules.maxLength(100)]"
                            counter="100"
                            :label="$t('persona._tercer_nombre_')"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.primer_apellido"
                            :rules="[rules.required, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('persona._primer_apellido_') + ' *'"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.segundo_apellido"
                            :rules="[rules.maxLength(100)]"
                            counter="100"
                            :label="$t('persona._segundo_apellido_')"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.tercer_apellido"
                            :rules="[rules.maxLength(100)]"
                            counter="100"
                            :label="$t('persona._tercer_apellido_')"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-select
                            :label="$t('persona._sexo_')"
                            :items="props.sexos"
                            v-model="formData.sexo_id"
                            item-title="descripcion"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-select>
                        <v-locale-provider locale="es">
                            <v-date-input
                                clearable
                                required
                                v-model="formData.fecha_nacimiento"
                                :label="$t('persona._fecha_nacimiento_') + ' *'"
                            ></v-date-input>
                        </v-locale-provider>
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
