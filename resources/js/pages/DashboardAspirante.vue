<template>
    <v-stepper-vertical :items="['Paso 1', 'Paso 2', 'Paso 3']">
        <template v-slot:item.1="{ step }"> Hola </template>
        <template v-slot:next="{ next }">
            <v-btn color="primary" @click="next"></v-btn>
        </template>
        <template v-slot:prev="{ prev }">
            <v-btn v-if="!finished" variant="plain" @click="prev"></v-btn>

            <v-btn v-else text="Reset" variant="plain" @click="finished = false"></v-btn>
        </template>
    </v-stepper-vertical>
</template>
<script setup>
import { shallowRef } from 'vue';

const finished = shallowRef(false);

function onClickFinish() {
    finished.value = true;

    alert('Finished');
}
</script>
