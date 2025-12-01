<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';
import { useFilteredMerge } from '@/composables/useFilteredMerge';
import { usePage } from '@inertiajs/vue3';
import { usePermissions } from '@/composables/usePermissions';

const { hasPermission } = usePermissions();
const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();
const { filteredAssign } = useFilteredMerge();

const page = usePage();
const roles = page.props.auth.roles;

const loading = ref(false);
const formRef = ref<VForm | null>(null);

const emit = defineEmits(['form-saved']);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
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
    email_cuenta_usuario: string;
    perfil: string;
    permitir_editar: boolean;
}

const props = defineProps(['item', 'accion', 'sexos', 'perfil']);

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
    email_cuenta_usuario: '',
    perfil: '',
    permitir_editar: false,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    formData.value.perfil = props.perfil;
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('administracion-perfil-save'), formData.value);
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
            const msj = axios.isAxiosError(error) ? error.response?.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

const isEstudiante = computed(() => roles.includes('estudiante'));
const isDocente = computed(() => roles.includes('docente'));
const permitirEditar = computed(() => (isEstudiante.value || isDocente.value) && !props.item.permitir_editar ? false : true);

onMounted(() => {
    reset();

    if (props.item) {
        filteredAssign(formData.value, props.item);
    }

    if (isEditing.value && props.item.usuarios.length > 0) {
        formData.value.email_cuenta_usuario = props.item.usuarios[0].email;
    }
});
</script>
<template>
    <v-card :title="$t('perfil._datos_personales_')">
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
                            :label="$t('perfil._primer_nombre_') + ' *'"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.segundo_nombre"
                            :rules="[rules.maxLength(100)]"
                            counter="100"
                            :label="$t('perfil._segundo_nombre_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.tercer_nombre"
                            :rules="[rules.maxLength(100)]"
                            counter="100"
                            :label="$t('perfil._tercer_nombre_')"
                            :disabled="!permitirEditar"
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
                            :label="$t('perfil._primer_apellido_') + ' *'"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.segundo_apellido"
                            :rules="[rules.maxLength(100)]"
                            counter="100"
                            :label="$t('perfil._segundo_apellido_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.tercer_apellido"
                            :rules="[rules.maxLength(100)]"
                            counter="100"
                            :label="$t('perfil._tercer_apellido_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6" v-if="!isEditing">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-at"
                            v-model="formData.email_cuenta_usuario"
                            :rules="[rules.required, rules.email, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('perfil._email_cuenta_usuario_') + ' *'"
                            :hint="$t('perfil._email_cuenta_usuario_hint_')"
                            persistent-hint
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-select
                            :label="$t('perfil._sexo_')"
                            :items="props.sexos"
                            v-model="formData.sexo_id"
                            item-title="descripcion"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                            :disabled="!permitirEditar"
                        ></v-select>
                        <v-locale-provider locale="es">
                            <v-date-input
                                clearable
                                required
                                v-model="formData.fecha_nacimiento"
                                :label="$t('perfil._fecha_nacimiento_') + ' *'"
                                :disabled="!permitirEditar"
                            ></v-date-input>
                        </v-locale-provider>
                        <v-checkbox v-if="hasPermission('ADMINISTRACION_PERFIL_AUTORIZAR_EDICION_DATOS-PERSONALES')"
                            v-model="formData.permitir_editar"
                            :label="$t('perfil._permitir_edicion_')"
                        ></v-checkbox>
                    </v-col>

                    <v-col cols="12" align="right">
                        <v-btn v-if="permitirEditar" :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                            {{ $t('_guardar_') }}
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </template>
    </v-card>
</template>
