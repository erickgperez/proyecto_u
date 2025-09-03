<script setup lang="ts">
import { ref, toRef } from 'vue';

const loading = ref(false);

function createNewRecord() {
    return {
        id: '',
        nombre: '',
        descripcion: '',
        fecha: '',
        cuerpo_mensaje: '',
        afiche: '',
    };
}

function reset() {
    //dialog.value = false;
    formModel.value = createNewRecord();
    items.value = [];
}

const formModel = ref(createNewRecord());
const isEditing = toRef(() => !!formModel.value.id);

function edit(id) {
    const found = items.value.find((item) => item.id === id);

    formModel.value = {
        id: found.id,
        nombre: found.nombre,
        descripcion: found.descripcion,
        fecha: found.fecha,
        cuerpo_mensaje: found.cuerpo_mensaje,
        afiche: found.afiche,
    };
}

// ***********************************************

function add() {
    formModel.value = createNewRecord();
}

function submitForm() {
    if (isEditing.value) {
        const index = items.value.findIndex((item) => item.id === formModel.value.id);
        items.value[index] = formModel.value;
    } else {
        formModel.value.id = items.value.length + 1;
        items.value.push(formModel.value);
    }
}

onMounted(() => {
    reset();
});
</script>
<template>
    <v-card :title="`${isEditing ? 'Editar' : 'Agregar'} convocatoria`">
        <template v-slot:text>
            <v-form fast-fail @submit.prevent="submitForm" ref="formRef">
                <v-date-input
                    clearable
                    required
                    v-model="formModel.fecha"
                    :rules="[(v) => !!v || 'La fecha es requerida']"
                    label="Fecha *"
                ></v-date-input>

                <v-text-field
                    required
                    v-model="formModel.nombre"
                    :rules="[
                        (v) => !!v || 'El nombre de la convocatoria es requerido',
                        (v) => (!!v && v.length <= 100) || 'Longitud máxima de 100 caracteres',
                    ]"
                    counter="100"
                    label="Nombre *"
                ></v-text-field>
                <v-text-field
                    v-model="formModel.descripcion"
                    :rules="[(v) => v.length <= 100 || 'Longitud máxima de 255 caracteres']"
                    counter="255"
                    label="Descripción"
                ></v-text-field>
            </v-form>
        </template>

        <v-divider></v-divider>

        <v-card-actions class="bg-surface-light">
            <v-btn text="Cancelar" variant="plain" @click="dialog = false"></v-btn>

            <v-spacer></v-spacer>

            <v-btn :loading="loading" type="submit" rounded variant="tonal" color="blue-darken-4">Guardar</v-btn>
        </v-card-actions>
    </v-card>
</template>
