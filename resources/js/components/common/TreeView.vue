<script setup lang="ts">
import { onMounted, ref } from 'vue';

defineEmits(['update:modelValue']);

const props = defineProps(['items', 'seleccionados']);

const selected = ref([]);
const search = ref('');

onMounted(() => {
    selected.value = props.seleccionados;
});
</script>
<template>
    <v-sheet class="pa-1">
        <v-text-field
            v-model="search"
            clear-icon="mdi-close-circle-outline"
            variant="outlined"
            clearable
            flat
            hide-details
            density="compact"
            prepend-inner-icon="mdi-magnify"
            rounded="xl"
            class="w-50"
        ></v-text-field>
    </v-sheet>

    <v-sheet class="overflow-y-auto" style="max-height: 500px">
        <v-treeview
            v-model:selected="selected"
            @update:model-value="$emit('update:modelValue', $event)"
            :items="props.items"
            item-value="id"
            select-strategy="classic"
            selectable
            :indent-lines="true"
            :search="search"
            open-all
        >
            <template v-slot:toggle="{ props: toggleProps, isOpen, isSelected, isIndeterminate }">
                <v-badge :color="isSelected ? 'success' : 'warning'" :model-value="isSelected || isIndeterminate">
                    <template v-slot:badge>
                        <v-icon v-if="isSelected" icon="$complete"></v-icon>
                    </template>
                    <v-btn
                        v-bind="toggleProps"
                        :color="isIndeterminate ? 'warning' : isSelected ? 'success' : 'medium-emphasis'"
                        :variant="isOpen ? 'outlined' : 'tonal'"
                    ></v-btn>
                </v-badge>
            </template>
        </v-treeview>
    </v-sheet>
</template>
