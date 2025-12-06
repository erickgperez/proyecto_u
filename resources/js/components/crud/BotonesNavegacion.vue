<script setup lang="ts">
const emit = defineEmits(['nextStep']);

const props = defineProps({
    selectedAction: {
        type: String,
        required: true,
        default: () => '',
    },
    step: {
        type: Number,
        required: true,
    },
});
</script>
<template>
    <div class="pa-2">
        <v-btn
            v-if="props.step > 1 && props.selectedAction != 'new'"
            prepend-icon="mdi-arrow-left-bold"
            rounded
            variant="tonal"
            color="blue-darken-4"
            @click="emit('nextStep', props.step - 1)"
        >
            <template v-slot:prepend>
                <v-icon color="success"></v-icon>
            </template>
            {{ $t('_atras_') }}
        </v-btn>
        <v-btn
            v-if="props.step == 3"
            prepend-icon="mdi-page-first"
            rounded
            variant="tonal"
            color="blue-darken-4"
            @click="emit('nextStep', 1)"
            class="ml-2"
        >
            <template v-slot:prepend>
                <v-icon color="success"></v-icon>
            </template>
            {{ $t('_regresar_listado_') }}
        </v-btn>
    </div>
</template>
