<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

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

    if (valid) {
        try {
            const resp = await axios.post(route('seguridad-usuario-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            console.log(error);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + error.message);
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
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.name"
                            :rules="[rules.required, rules.maxLength(255)]"
                            counter="100"
                            :label="$t('persona._nombre_') + ' *'"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.email"
                            :rules="[rules.required, rules.maxLength(255)]"
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
