<script setup lang="ts">
import { useFilteredMerge } from '@/composables/useFilteredMerge';
import { useFunciones } from '@/composables/useFunciones';
import { usePermissions } from '@/composables/usePermissions';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';

const { hasPermission } = usePermissions();
const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();
const { filteredAssign } = useFilteredMerge();
const page = usePage();
const roles = page.props.auth.roles;

const loading = ref(false);
const formRef = ref<VForm | null>(null);
const distrito = computed(() => (formData.value.residencia_distrito?.length > 0 ? formData.value.residencia_distrito[0].nombreCompleto : ''));
const dialogTreeVisible = ref(false);

const emit = defineEmits(['form-saved']);

function reset() {
    if (formRef.value) {
        formRef.value.reset();
    }
}

interface FormData {
    id: number | null;
    //email_principal: string;
    email_alternativo: string;
    direccion_residencia: string;
    residencia_distrito: Array<object[]> | null;
    residencia_distrito_id: number | null;
    telefono_residencia: string;
    direccion_trabajo: string;
    telefono_trabajo: string;
    telefono_personal: string;
    telefono_personal_alternativo: string;
    persona_id: number | null;
    permitir_editar: boolean;
}

const props = defineProps(['item', 'accion', 'distritosTree', 'guardarTxt']);

const formData = ref<FormData>({
    id: null,
    //email_principal: '',
    email_alternativo: '',
    direccion_residencia: '',
    residencia_distrito: [],
    residencia_distrito_id: null,
    telefono_residencia: '',
    direccion_trabajo: '',
    telefono_trabajo: '',
    telefono_personal: '',
    telefono_personal_alternativo: '',
    persona_id: null,
    permitir_editar: false,
});

const isEstudiante = computed(() => roles.includes('estudiante'));
const isDocente = computed(() => roles.includes('docente'));
const permitirEditar = computed(() => ((isEstudiante.value || isDocente.value) && !props.item.permitir_editar ? false : true));

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            if (formData.value.residencia_distrito?.length > 0) {
                formData.value.residencia_distrito_id = formData.value.residencia_distrito[0].id;
            }

            const resp = await axios.post(route('administracion-perfil-datos-contacto-save', { id: props.item.uuid }), formData.value);
            if (resp.data.status == 'ok') {
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

onMounted(() => {
    reset();

    if (props.item.datos_contacto) {
        filteredAssign(formData.value, props.item.datos_contacto);
    }

    if (formData.value.residencia_distrito_id) {
        const distrito_ = props.item.datos_contacto.distrito_residencia;
        formData.value.residencia_distrito = [distrito_];
    }
});
</script>
<template>
    <v-card :title="$vuetify.display.mobile ? $t('perfil._datos_contacto_') : ''">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <!--<v-col cols="12" md="6">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-at"
                            v-model="formData.email_principal"
                            :rules="[rules.required, rules.email, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('perfil._email_principal_') + ' *'"
                        ></v-text-field>
                    </v-col>-->
                    <v-col cols="12" md="6">
                        <v-text-field
                            prepend-icon="mdi-at"
                            v-model="formData.email_alternativo"
                            :rules="[rules.email, rules.maxLength(100)]"
                            counter="100"
                            :label="$t('perfil._email_alternativo_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-text-field
                            prepend-icon="mdi-cellphone-sound"
                            v-model="formData.telefono_personal"
                            :rules="[rules.maxLength(50)]"
                            counter="50"
                            :label="$t('perfil._telefono_personal_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-text-field
                            prepend-icon="mdi-cellphone-sound"
                            v-model="formData.telefono_personal_alternativo"
                            :rules="[rules.maxLength(50)]"
                            counter="50"
                            :label="$t('perfil._telefono_personal_alternativo_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-text-field
                            prepend-icon="mdi-home"
                            v-model="formData.direccion_residencia"
                            :rules="[rules.maxLength(500)]"
                            counter="500"
                            :label="$t('perfil._direccion_residencia_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-text-field
                            prepend-icon="mdi-cellphone-basic"
                            v-model="formData.telefono_residencia"
                            :rules="[rules.maxLength(50)]"
                            counter="50"
                            :label="$t('perfil._telefono_residencia_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            prepend-icon="mdi-home-city"
                            :model-value="distrito"
                            readonly
                            :label="$t('perfil._distrito_residencia_')"
                            :disabled="!permitirEditar"
                        >
                        </v-text-field>
                    </v-col>
                    <v-col cols="6" v-if="permitirEditar">
                        <v-btn @click="dialogTreeVisible = true" color="primary">{{ $t('perfil._seleccionar_distrito_') }}</v-btn>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-office-building-outline"
                            v-model="formData.direccion_trabajo"
                            :rules="[rules.maxLength(50)]"
                            counter="500"
                            :label="$t('perfil._direccion_trabajo_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-text-field
                            prepend-icon="mdi-deskphone"
                            v-model="formData.telefono_trabajo"
                            :rules="[rules.maxLength(50)]"
                            counter="50"
                            :label="$t('perfil._telefono_trabajo_')"
                            :disabled="!permitirEditar"
                        ></v-text-field>
                    </v-col>
                    <v-checkbox
                        v-if="hasPermission('ADMINISTRACION_PERFIL_AUTORIZAR_EDICION_DATOS-CONTACTO')"
                        v-model="formData.permitir_editar"
                        :label="$t('perfil._permitir_edicion_')"
                    ></v-checkbox>
                    <v-col cols="12" align="right">
                        <v-btn
                            v-if="permitirEditar"
                            :loading="loading"
                            type="submit"
                            rounded
                            variant="tonal"
                            color="blue-darken-4"
                            prepend-icon="mdi-content-save"
                        >
                            {{ props.guardarTxt != null ? guardarTxt : $t('_guardar_') }}
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </template>
    </v-card>
    <v-dialog v-model="dialogTreeVisible" max-width="500">
        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-text>
                    <v-treeview
                        v-model:selected="formData.residencia_distrito"
                        :items="props.distritosTree"
                        selectable
                        select-strategy="single-leaf"
                        item-value="id"
                        item-title="descripcion"
                        density="compact"
                        return-object
                    ></v-treeview>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" @click="isActive.value = false">{{ $t('_cerrar_') }}</v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>
