<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, ref, toRef } from 'vue';
import { useI18n } from 'vue-i18n';
import type { VForm } from 'vuetify/components';
import TreeView from '../common/TreeView.vue';

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
    permisos: [];
}

const props = defineProps(['item', 'accion', 'permisosMenu', 'permisosModulo', 'permisosApp']);

const formData = ref<FormData>({
    id: null,
    name: '',
    permisos: [],
});
const tab = ref(null);
const openApp = ref([]);
const openMenu = ref([]);
const search = ref('');
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.post(route('seguridad-roles-save'), formData.value);
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
        formData.value.permisos = props.item.permisos.map((p: any) => p.id + '');
        openApp.value = props.permisosApp.map((item) => item.id);
        openMenu.value = props.permisosMenu.map((item) => item.id);
    }
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('rol._editar_') : $t('rol._crear_')} `">
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
                    <!--<v-autocomplete
                        clearable
                        :label="$t('rol._permisos_')"
                        :items="props.permisos"
                        v-model="formData.permisos"
                        item-title="name"
                        item-value="id"
                        prepend-icon="mdi-form-dropdown"
                        multiple
                        chips
                        persistent-hint
                    ></v-autocomplete>-->

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
