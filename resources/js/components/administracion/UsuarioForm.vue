<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    formRef.value!.reset();
}

interface FormData {
    id: number | null;
    name: string;
    email: string;
    persona_id: number | null;
    roles: [];
}

const props = defineProps(['item', 'accion', 'personas', 'roles']);

const formData = ref<FormData>({
    id: null,
    name: '',
    email: '',
    persona_id: null,
    roles: [],
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.post(route('seguridad-usuario-save'), formData.value);
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
        formData.value.persona_id = props.item.personas[0]?.id;
    }
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('usuario._editar_') : $t('usuario._crear_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.name"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => (!!v && v.length <= 255) || $t('_longitud_maxima_') + ': 255 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('persona._nombre_') + ' *'"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field
                            required
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.email"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => (!!v && v.length <= 255) || $t('_longitud_maxima_') + ': 255 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('usuario._email_') + ' *'"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-select
                            :label="$t('usuario._asignado_a_')"
                            :items="props.personas"
                            v-model="formData.persona_id"
                            item-title="nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-select>
                    </v-col>

                    <v-autocomplete
                        clearable
                        :label="$t('rol._plural_')"
                        :items="props.roles"
                        v-model="formData.roles"
                        item-title="name"
                        item-value="id"
                        prepend-icon="mdi-form-dropdown"
                        multiple
                        chips
                    ></v-autocomplete>

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
