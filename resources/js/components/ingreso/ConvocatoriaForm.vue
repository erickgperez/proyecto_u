<script setup lang="ts">
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted, reactive, ref, toRef } from 'vue';
import type { VForm } from 'vuetify/components';

const loading = ref(false);
const formRef = ref<VForm | null>(null);

/*function createNewRecord() {
    return {
        id: '',
        nombre: '',
        descripcion: '',
        fecha: '',
        cuerpo_mensaje: '',
        afiche: null,
    };
}*/

function reset() {
    //formData.value = createNewRecord();
}

interface FormData {
    id: number | null;
    nombre: string;
    descripcion: string;
    fecha: Date | null;
    cuerpo_mensaje: string;
    afiche: File | null;
}

const props = defineProps(['item']);

const formData: FormData = reactive({
    id: null,
    nombre: '',
    descripcion: '',
    fecha: null,
    cuerpo_mensaje: '',
    afiche: null,
});
const isEditing = toRef(() => !!formData.id);

function edit(id) {
    const found = items.value.find((item) => item.id === id);

    /*formModel.value = {
        id: found.id,
        nombre: found.nombre,
        descripcion: found.descripcion,
        fecha: found.fecha,
        cuerpo_mensaje: found.cuerpo_mensaje,
        afiche: found.afiche,
    };*/
}

/*function add() {
    formModel.value = createNewRecord();
}*/

async function submitForm() {
    console.log(isEditing.value);

    const { valid } = await formRef.value!.validate();
    loading.value = true;
    if (valid) {
        try {
            const response = await axios.postForm(route('ingreso-convocatoria-save'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            console.log(response);
            formRef.value!.reset();
            formData.afiche = null;
            reset();

            Swal.fire({
                title: 'Éxito',
                text: 'Datos subidos correctamente',
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                toast: true,
            });
        } catch (error: any) {
            Swal.fire({
                title: 'Error',
                text: 'No se ha podido guardar el formulario (' + error.response.data.message + ')',
                icon: 'error',
                confirmButtonColor: '#D7E1EE',
            });
        }
    }
    loading.value = false;
    if (isEditing.value) {
        //const index = items.value.findIndex((item) => item.id === formModel.value.id);
        //items.value[index] = formModel.value;
    } else {
        //formModel.value.id = items.value.length + 1;
        //items.value.push(formModel.value);
    }
}

onMounted(() => {
    reset();
    formData.id = props.item.id;
    formData.fecha = props.item.fecha;
    formData.nombre = props.item.nombre;
    formData.descripcion = props.item.descripcion;
    formData.cuerpo_mensaje = props.item.cuerpo_mensaje;
    //formData.afiche = props.item.afiche; //El tratamiento del archivo será diferente
});
</script>
<template>
    <v-card :title="`${isEditing ? $t('_editar_convocatoria_') : $t('_agregar_convocatoria_')} `">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-row>
                    <v-col cols="12">
                        <v-locale-provider locale="es">
                            <v-date-input
                                clearable
                                required
                                v-model="formData.fecha"
                                :rules="[(v) => !!v || $t('_fecha_requerida_')]"
                                :label="$t('_fecha_') + ' *'"
                            ></v-date-input>
                        </v-locale-provider>

                        <v-text-field
                            required
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.nombre"
                            :rules="[
                                (v) => !!v || 'El nombre de la convocatoria es requerido',
                                (v) => (!!v && v.length <= 100) || 'Longitud máxima de 100 caracteres',
                            ]"
                            counter="100"
                            label="Nombre *"
                        ></v-text-field>

                        <v-text-field
                            prepend-icon="mdi-form-textbox"
                            v-model="formData.descripcion"
                            :rules="[(v) => v.length <= 255 || 'Longitud máxima de 255 caracteres']"
                            counter="255"
                            label="Descripción"
                        ></v-text-field>

                        <v-textarea
                            prepend-icon="mdi-form-textarea"
                            label="Cuerpo del mensaje"
                            v-model="formData.cuerpo_mensaje"
                            hint="Mensaje que se enviará por correo electrónico cuando se hagan invitaciones a la convocatoria"
                            persistent-hint
                        ></v-textarea>
                    </v-col>
                    <v-col cols="12">
                        <v-file-input
                            label="Afiche en formato PDF"
                            accept=".pdf"
                            clearable
                            v-model="formData.afiche"
                            @input="formData.afiche = $event.target.files[0]"
                            show-size
                            counter
                        ></v-file-input>
                    </v-col>
                    <v-col cols="12" align="right">
                        <v-btn :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-content-save">
                            Guardar
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </template>
    </v-card>
</template>
