<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';
import TreeView from '../common/TreeView.vue';

const { t } = useI18n();
const { rules, mensajeExito, mensajeError } = useFunciones();

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
    name: string;
    permisos: [];
}

const props = defineProps(['item', 'accion', 'permisosMenu', 'permisosModulo', 'permisosApp']);

const formData = ref<FormData>({
    id: null,
    name: '',
    permisos: [],
});
const tab = ref(null);
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('seguridad-roles-save'), formData.value);
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

onMounted(() => {
    reset();
    if (props.accion === 'edit') {
        formData.value = { ...props.item };
        formData.value.permisos = props.item.permisos.map((p: any) => p.id + '');
    }
});

const titleForm = computed(() => {
    return isEditing.value ? t('rol._editar_') : t('rol._crear_');
});
</script>
<template>
    <v-card class="rounded-t-xl">
        <v-card-title class="border-b-md bg-blue-grey-lighten-3">
            {{ titleForm }}
        </v-card-title>
        <v-card-text class="pt-4">
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.name"
                            :rules="[rules.required, rules.maxLength(255)]"
                            counter="255"
                            :label="$t('rol._nombre_') + ' *'"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-card>
                            <v-tabs v-model="tab" align-tabs="center" color="basil" grow>
                                <v-tab value="one">{{ $t('auth._permisos_modulos_') }}</v-tab>
                                <v-tab value="two">{{ $t('auth._permisos_menus_') }}</v-tab>
                                <v-tab value="three">{{ $t('auth._permisos_aplicaciones_') }}</v-tab>
                            </v-tabs>

                            <v-card-text>
                                <v-tabs-window v-model="tab">
                                    <v-tabs-window-item value="one">
                                        <TreeView
                                            :items="props.permisosModulo"
                                            v-model="formData.permisos"
                                            :seleccionados="formData.permisos"
                                        ></TreeView>
                                    </v-tabs-window-item>

                                    <v-tabs-window-item value="two">
                                        <TreeView
                                            :items="props.permisosMenu"
                                            v-model="formData.permisos"
                                            :seleccionados="formData.permisos"
                                        ></TreeView>
                                    </v-tabs-window-item>

                                    <v-tabs-window-item value="three">
                                        <TreeView
                                            :items="props.permisosApp"
                                            v-model="formData.permisos"
                                            :seleccionados="formData.permisos"
                                        ></TreeView>
                                    </v-tabs-window-item>
                                </v-tabs-window>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <v-col cols="12" align="right">
                        <v-btn :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                            {{ $t('_guardar_') }}
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </v-card-text>
    </v-card>
</template>
