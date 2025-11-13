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
    if (formRef.value) {
        formRef.value.reset();
    }
}

interface FormData {
    id: number | null;
    codigo: string;
    nombre: string;
    creditos: number;
    tipo_unidad_academica_id: number | null;
}

const props = defineProps(['item', 'accion', 'tipos']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    creditos: 0,
    tipo_unidad_academica_id: null,
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('plan_estudio-unidad_academica-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);
                //localItem.value.afiche = resp.data.convocatoria.afiche;
                mensajeExito(t('_datos_subidos_correctamente_'));
            } else {
                console.log(resp.data.message);
                throw new Error(resp.data.message);
            }
        } catch (error: any) {
            const msj = axios.isAxiosError(error) ? error.response.data.message : t(error.message);
            mensajeError(t('_no_se_pudo_guardar_formulario_') + '. ' + msj);
        }
    }
    loading.value = false;
}

onMounted(() => {
    reset();

    formData.value = { ...props.item };

    if (isEditing.value) {
        formData.value.creditos = Number(props.item.creditos);
        formData.value.tipo_unidad_academica_id = props.item.tipo;
    } else {
        const tipoAsignatura = props.tipos.filter((t) => t.codigo === 'ASIGNATURA');
        if (tipoAsignatura.length === 1) {
            formData.value.tipo_unidad_academica_id = tipoAsignatura[0];
        }
    }

    /**/
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('unidadAcademica._editar_') : $t('unidadAcademica._crear_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[rules.required, rules.maxLength(50)]"
                            counter="50"
                            :label="$t('_codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[rules.required]"
                            :label="$t('_nombre_') + ' *'"
                        ></v-text-field>
                        <v-locale-provider locale="en">
                            <v-number-input
                                required
                                icon-color="deep-orange"
                                prepend-icon="mdi-form-textbox"
                                v-model="formData.creditos"
                                :rules="[rules.required]"
                                :min="0"
                                :precision="null"
                                :label="$t('unidadAcademica._creditos_') + ' *'"
                            ></v-number-input>
                        </v-locale-provider>
                        <!--<v-autocomplete
                            clearable
                            :label="$t('unidadAcademica._tipo_') + ' *'"
                            :items="props.tipos"
                            v-model="formData.tipo_unidad_academica_id"
                            :rules="[rules.required]"
                            icon-color="deep-orange"
                            item-title="descripcion"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>-->
                        <span class="subheading ml-10">{{ $t('unidadAcademica._tipo_') + ' *' }}</span>
                        <v-chip-group
                            class="ml-10"
                            selected-class="v-chip--selected v-chip--variant-tonal text-deep-purple-accent-3"
                            variant="outlined"
                            mandatory
                            v-model="formData.tipo_unidad_academica_id"
                        >
                            <v-chip v-for="tipo in props.tipos" :key="tipo.id" :text="tipo.descripcion" :value="tipo" filter></v-chip>
                        </v-chip-group>
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
