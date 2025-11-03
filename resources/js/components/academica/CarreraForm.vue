<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import axios from 'axios';
import { computed, onMounted, ref, toRef, watch } from 'vue';
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
    codigo: string;
    nombre: string;
    certificacion_de: number | null;
    tipo_carrera_id: number | null;
    sedes: [];
}

const props = defineProps(['item', 'accion', 'carreras', 'tiposCarrera', 'sedes']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    certificacion_de: null,
    tipo_carrera_id: null,
    sedes: [],
});
const isEditing = toRef(() => props.accion === 'edit');

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    loading.value = true;

    if (valid) {
        try {
            const resp = await axios.post(route('plan_estudio-carrera-save'), formData.value);
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

const carrerasFiltradas = computed(() => {
    return props.carreras.filter((carrera: any) => carrera.id != formData.value.id);
});

onMounted(() => {
    reset();
    if (props.accion === 'edit') {
        formData.value = { ...props.item };
    }
});

watch(
    () => props.accion,
    (newValue) => {
        if (newValue == 'new') {
            reset();
        }
    },
);
</script>
<template>
    <v-card :title="`${isEditing ? $t('carrera._editar_') : $t('carrera._crear_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[rules.required, rules.maxLength(30)]"
                            counter="20"
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

                        <v-autocomplete
                            clearable
                            :label="$t('carrera._tipo_') + ' *'"
                            :items="props.tiposCarrera"
                            v-model="formData.tipo_carrera_id"
                            :rules="[rules.required]"
                            icon-color="deep-orange"
                            item-title="descripcion"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>

                        <v-autocomplete
                            clearable
                            :label="$t('carrera._padre_')"
                            :items="carrerasFiltradas"
                            v-model="formData.certificacion_de"
                            item-title="nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                        ></v-autocomplete>

                        <v-autocomplete
                            clearable
                            :label="$t('sede._sedes_')"
                            :items="props.sedes"
                            v-model="formData.sedes"
                            item-title="nombre"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                            multiple
                            chips
                            :hint="$t('carrera._sedes_ayuda_')"
                            persistent-hint
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
