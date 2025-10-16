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
    codigo: string;
    nombre: string;
    carreras: [];
    carrerasIds: [];
}

const props = defineProps(['item', 'accion', 'tipos', 'carrerasCupo']);

const formData = ref<FormData>({
    id: null,
    codigo: '',
    nombre: '',
    carreras: [],
    carrerasIds: [],
});
const isEditing = toRef(() => props.accion === 'edit');
const carrerasCupo_ = ref(props.carrerasCupo);

async function submitForm() {
    const { valid } = await formRef.value!.validate();
    formData.value.carreras = carrerasCupo_.value.filter((c: any) => formData.value.carrerasIds.includes(c.id));
    loading.value = true;

    const hasError = ref(false);
    const message = ref('');

    if (valid) {
        try {
            const resp = await axios.post(route('academica-sede-save'), formData.value);
            if (resp.data.status == 'ok') {
                if (!isEditing.value) {
                    reset();
                }
                emit('form-saved', resp.data.item);
                //localItem.value.afiche = resp.data.convocatoria.afiche;
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
        formData.value.carrerasIds = props.item.carreras.map((cs: any) => cs.id);

        //Actualizar el cupo de las carreras según la sede seleccionada
        carrerasCupo_.value = props.carrerasCupo.map((item: any) => {
            //Buscar la carrera en la sede
            const matchingUpdate = props.item.carreras.find((newItemCupo: any) => newItemCupo.id === item.id);
            if (matchingUpdate) {
                //Retornar el item con el campo cupo actualizado
                return { ...item, cupo: matchingUpdate.pivot.cupo ?? 1 };
            }
            //retornar el item original
            return item;
        });
    }
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('_editar_sede_') : $t('_crear_sede_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.codigo"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => !v || v.length <= 20 || $t('_longitud_maxima_') + ': 20 ' + $t('_caracteres_'),
                            ]"
                            counter="20"
                            :label="$t('_codigo_') + ' *'"
                        ></v-text-field>

                        <v-text-field
                            required
                            icon-color="deep-orange"
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[
                                (v) => !!v || $t('_campo_requerido_'),
                                (v) => (!!v && v.length <= 255) || $t('_longitud_maxima') + ': 255 ' + $t('_caracteres_'),
                            ]"
                            counter="100"
                            :label="$t('_nombre_') + ' *'"
                        ></v-text-field>

                        <!--<v-autocomplete
                            clearable
                            :label="$t('sede._carreras_')"
                            :items="props.carreras"
                            v-model="formData.carrerasIds"
                            item-title="nombreCompleto"
                            item-value="id"
                            prepend-icon="mdi-form-dropdown"
                            multiple
                            chips
                            :hint="$t('sede._carreras_ayuda_')"
                            persistent-hint
                        ></v-autocomplete>-->
                        {{ $t('sede._carreras_ayuda_') }}
                        <v-list v-model:selected="formData.carrerasIds" select-strategy="leaf" v-for="tipo in props.tipos" :key="tipo">
                            <v-list-subheader>{{ tipo }}</v-list-subheader>
                            <v-list-item
                                v-for="item in carrerasCupo_.filter((c) => c.tipo.codigo === tipo)"
                                :key="item.id"
                                :title="item.nombreCompleto"
                                :value="item.id"
                                active-class="text-primary"
                            >
                                <template v-slot:prepend="{ isSelected, select }">
                                    <v-list-item-action start>
                                        <v-checkbox-btn :model-value="isSelected" @update:model-value="select"></v-checkbox-btn>
                                    </v-list-item-action>
                                </template>
                                <template v-slot:append="{ isSelected }">
                                    <v-list-item-action class="flex-column" v-if="isSelected">
                                        <small class="text-high-emphasis">{{ $t('_cupo_') }}</small>
                                        <v-number-input :min="1" control-variant="split" inset v-model="item.cupo"></v-number-input>
                                    </v-list-item-action>
                                </template>
                            </v-list-item>
                        </v-list>
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
